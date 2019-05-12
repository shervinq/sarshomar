<?php
namespace lib\app;


class analyze
{

	private static function setting_detect($_survey_detail)
	{
		$setting = [];

		if(isset($_survey_detail['setting']) && is_string($_survey_detail['setting']))
		{
			$setting = json_decode($_survey_detail['setting'], true);
		}
		elseif(isset($_survey_detail['setting']) && is_array($_survey_detail['setting']))
		{
			$setting = $_survey_detail['setting'];
		}

		if(!is_array($setting))
		{
			$setting = [];
		}

		return $setting;
	}


	private static function end_step($_selectivecount, $_countblock)
	{
		if($_selectivecount)
		{
			return ['step' => $_selectivecount + 1];
		}
		else
		{
			return ['step' => $_countblock + 1];
		}
	}


	public static function question_step($_type, $_step, $_survey_detail, $_user_id)
	{
		if(!$_user_id || !is_numeric($_user_id) || !$_survey_detail)
		{
			return false;
		}

		if(!is_numeric($_step))
		{
			$_step = 0;
		}

		$_step           = intval($_step);

		// step get from the url
		// if user set manual step and this step is less than 0
		// we set the step force on 0
		if($_step < 0)
		{
			$_step = 0;
		}

		// survey detail
		$survey_id          = \dash\coding::decode($_survey_detail['id']);
		$question_id        = false;
		$question_detail    = [];

		// setting detail
		$setting            = self::setting_detect($_survey_detail);
		$randomquestion     = false;
		$selectivecount     = 0;
		$cannotreview       = false;
		$cannotupdateanswer = false;

		// new step must be loaded to user
		$new_step           = null;

		// load thankyou message - end step
		$thankyou           = false;

		// load wellcome messate - first step
		$wellcome           = false;


		// set this survey is my survey
		$mySurvey           = false;
		if(isset($_survey_detail['user_id']) && intval($_survey_detail['user_id']) === intval($_user_id))
		{
			$mySurvey = true;
		}
		// if survey is random question set it
		if(isset($setting['randomquestion']) && $setting['randomquestion'])
		{
			$randomquestion = true;
		}
		// set can not review question
		if(isset($setting['cannotreview']) && $setting['cannotreview'])
		{
			$cannotreview = true;
		}
		// set can not update answer
		if(isset($setting['cannotupdateanswer']) && $setting['cannotupdateanswer'])
		{
			$cannotupdateanswer = true;
		}
		// set selective count
		if(isset($setting['selectivecount']) && $setting['selectivecount'])
		{
			$selectivecount = intval($setting['selectivecount']);
		}

		// load all question count in this survey
		$countblock           = (isset($_survey_detail['countblock']) && $_survey_detail['countblock']) ? intval($_survey_detail['countblock'])      : 0;

		// if the survey time is ended return to end step
		if($_type === 'surveytime')
		{
			return ['step' => self::end_step($selectivecount, $countblock)];
		}
		elseif($_type === 'questiontime')
		{
			// if question time is ended return to next question
			return ['step' => intval($_step) + 1];
		}
		// load answer of this user
		$answer               = \lib\db\answers::get_user_answer($survey_id, $_user_id);
		// load saved question
		$saved_asked_question = isset($answer['questions']) ? $answer['questions'] : [];

		if(is_string($saved_asked_question))
		{
			$saved_asked_question = json_decode($saved_asked_question, true);
		}

		if(!is_array($saved_asked_question))
		{
			$saved_asked_question = [];
		}


		$must_step  = 1;
		// not random question mode
		if(!$randomquestion)
		{
			// simple survey

			if(isset($answer['step']) && $answer['step'])
			{
				$must_step = intval($answer['step']) + 1;
			}

			if($_step <= $must_step)
			{
				if($mySurvey)
				{
					$new_step = $_step;
				}
				else
				{
					// if allow review
					if(!$cannotreview)
					{
						$new_step = $_step;
					}
					else
					{
						$new_step = $must_step;
					}
				}
			}
			else
			{
				// the step is larger than must step
				if($mySurvey)
				{
					// to not load larger step in mySurvey
					if($_step > $countblock + 1)
					{
						$new_step = $must_step;
					}
					else
					{
						$new_step = $_step;
					}
				}
				else
				{
					$new_step = $must_step;
				}
			}


			if($new_step >= $countblock + 1)
			{
				$thankyou = true;
			}


			if(!$thankyou)
			{
				if($_type === 'answer')
				{
					$new_step++;
				}
				else
				{
					if(!$new_step)
					{
						$new_step = 1;
					}

					$question_detail = \lib\app\question::get_by_step(\dash\coding::encode($survey_id), $new_step);

					if(isset($question_detail['id']))
					{
						$question_id = \dash\coding::decode($question_detail['id']);
					}
				}
			}
		}
		else
		{

			// randomquestion mode
			$not_random_question_again = array_column($saved_asked_question, 'load_step');

			$step_key                  = 1;
			$must_step                 = 1;
			$last_step                 = null;

			if($saved_asked_question)
			{
				$mytemp    = end($saved_asked_question);
				$last_step = $mytemp['step'];
			}

			// first question
			if(empty($saved_asked_question))
			{
				$must_step = rand(1, $countblock);
				$step_key = 1;
			}
			elseif(isset($saved_asked_question[$_step]['load_step']))
			{
				// this step is loaded before
				// need to load that question
				$must_step = $saved_asked_question[$_step]['load_step'];
				$step_key  = $saved_asked_question[$_step]['step'];

				// can not review
				// return end step
				if($cannotreview)
				{
					$mytemp    = end($saved_asked_question);
					$must_step = $mytemp['load_step'];
					$step_key  = $mytemp['step'];
				}
			}
			else
			{
				// random new question
				$random = [];

				for ($i = 1; $i <= $countblock ; $i++)
				{
					// check to not loaded this question before
					if(!in_array($i, $not_random_question_again))
					{
						$random[] = $i;
					}
				}

				// no not loaded question
				if(empty($random) || ($selectivecount && count($saved_asked_question) >= $selectivecount))
				{
					$thankyou = true;
					// the step is end of loaded step
					$step_key  = end($saved_asked_question);
					$step_key  = intval($step_key['step']);
				}
				else
				{
					// get the random key in array and get the step from random
					$must_step = $random[array_rand($random)];

					// the step is end of loaded step + 1
					$step_key  = end($saved_asked_question);
					$step_key  = intval($step_key['step']) + 1;
				}

			}

			if($thankyou)
			{
				// to not load larger than countblock step
				if($selectivecount)
				{
					// the step not found maybe 2000
					if($step_key > $selectivecount + 1)
					{
						$new_step = $selectivecount + 1;
					}
					else
					{
						// the step is exist in survey question
						if($cannotreview)
						{
							$new_step = $selectivecount + 1;
						}
						else
						{
							// can review
							$new_step = $step_key;
						}
					}
				}
				else
				{
					// no selective
					if($step_key > $countblock + 1)
					{
						$new_step = $countblock + 1;
					}
					else
					{
						if($cannotreview)
						{
							$new_step = $countblock + 1;
						}
						else
						{
							$new_step = $step_key;
						}
					}
				}
			}
			else
			{

				$new_step = $step_key;

				if($_type === 'answer')
				{
					$new_step++;
				}
				else
				{
					if($cannotreview && $last_step <= $new_step)
					{
						$new_step = $last_step + 1;
					}

					$question_detail = \lib\app\question::get_by_step(\dash\coding::encode($survey_id), $must_step);

					if(isset($question_detail['id']))
					{
						$question_id = \dash\coding::decode($question_detail['id']);
					}

					$saved_asked_question[$step_key] = ['step' => $step_key, 'load_step' => $must_step, 'question_id' => $question_id];

					$saved_asked_question_json = json_encode($saved_asked_question, JSON_UNESCAPED_UNICODE);

					if(isset($answer['id']))
					{
						\lib\db\answers::update(['questions' => $saved_asked_question_json], $answer['id']);
					}
					else
					{
						$insert_answer =
						[
							'user_id'      => $_user_id,
							'survey_id'    => $survey_id,
							'startdate'    => self::dateNow(),
							'step'         => 1,
							'lastquestion' => $question_id,
							'status'       => 'start',
							'questions' => $saved_asked_question_json,
						];

						\lib\db\answers::insert($insert_answer);
					}
				}

			}

		}

		$result =
		[
			'ok'              => true,
			'step'            => $new_step,
			'question_id'     => $question_id,
			'question_detail' => $question_detail,
			'thankyou'        => $thankyou,
			'wellcome'        => $wellcome,

		];

		return $result;
	}


	public static function dateNow()
	{
		return date("Y-m-d H:i:s");
	}


}
?>