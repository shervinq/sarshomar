<?php
namespace lib\app;

/**
 * Class for answer.
 */
class answer
{
	use \lib\app\answer\datalist;
	use \lib\app\answer\get;

	private static $answer_score            = 0;
	private static $answer_score_multi      = [];
	private static $user_score              = [];
	private static $question_address_loaded = false;
	private static $question_address        = [];
	private static $user_address_answer     = [];


	public static function user_score($_survey_id, $_user_id)
	{
		if(is_numeric($_user_id) && is_numeric($_survey_id))
		{
			$userScore = \lib\db\answerdetails::get_user_score($_survey_id, $_user_id);
			return $userScore;
		}

		return null;
	}


	// replace @score by user score value in somewhere
	public static function replace_user_score($_title, $_survey_id, $_user_id)
	{
		if(\dash\url::content() !== 's')
		{
			return $_title;
		}

		if(strpos($_title, '@score') !== false)
		{
			if(isset(self::$user_score[$_user_id. '_'. $_survey_id]))
			{
				$userScore = self::$user_score[$_user_id. '_'. $_survey_id];
			}
			else
			{
				$userScore = self::user_score($_survey_id, $_user_id);
				self::$user_score[$_user_id. '_'. $_survey_id] = $userScore;

			}

			$_title = str_replace('@score', \dash\utility\human::fitNumber($userScore, false), $_title);
		}

		return $_title;
	}


	// replace user question answer in somewhere
	public static function replace_question_answer($_title, $_survey_id, $_user_id)
	{
		if(strpos($_title, '@') === false)
		{
			return $_title;
		}

		if(\dash\url::content() !== 's')
		{
			return $_title;
		}

		if(!self::$question_address_loaded)
		{
			self::$question_address_loaded = true;
			self::$question_address = \lib\db\questions::get_address($_survey_id);
		}

		if(!empty(self::$question_address))
		{
			foreach (self::$question_address as $question_id => $address)
			{
				if(strpos($_title, '@'.$address) !== false)
				{
					if(isset(self::$user_address_answer[$_user_id. '_'. $_survey_id]))
					{
						$userAnswer = self::$user_address_answer[$_user_id. '_'. $_survey_id];
					}
					else
					{
						$userAnswer = \lib\db\answerdetails::get_user_answer($_survey_id, $_user_id, $question_id);
						self::$user_address_answer[$_user_id. '_'. $_survey_id] = $userAnswer;

					}

					$_title = str_replace('@'.$address, $userAnswer, $_title);
				}
			}
		}

		return $_title;
	}


	public static function dateNow()
	{
		return date("Y-m-d H:i:s");
	}


	public static function my_answer($_survey_id, $_question_id)
	{
		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to conitinue"));
			return false;
		}

		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			\dash\notif::error(T_("Survay id not set"));
			return false;
		}

		$question_id = \dash\coding::decode($_question_id);
		if(!$question_id)
		{
			\dash\notif::error(T_("Question id not set"));
			return false;
		}

		$get_answer =
		[
			'answerdetails.question_id' => $question_id,
			'answerdetails.user_id'     => \dash\user::id(),
			'answerdetails.survey_id'   => $survey_id,
			// 'limit'                     => 1,
		];

		$old_answer_detail = \lib\db\answerdetails::get_join($get_answer);

		if(!$old_answer_detail)
		{
			return null;
		}

		return $old_answer_detail;


	}


	public static function add($_survey_id, $_question_id, $_args)
	{
		// check user login
		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to conitinue"));
			return false;
		}

		// check survey id
		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			\dash\notif::error(T_("Survay id not set"));
			return false;
		}

		// check question id
		$question_id = \dash\coding::decode($_question_id);
		if(!$question_id)
		{
			\dash\notif::error(T_("Invalid question id"));
			return false;
		}

		// load survey
		$survey_detail = \lib\app\survey::get($_survey_id);

		if(!$survey_detail)
		{
			\dash\notif::error(T_("Invalid survey id"));
			return false;
		}

		// load survey setting
		if(isset($survey_detail['setting']) && is_string($survey_detail['setting']))
		{
			$survey_detail['setting'] = json_decode($survey_detail['setting'], true);
		}

		// check force login and mobile limited
		if(isset($survey_detail['setting']['forcelogin']) && $survey_detail['setting']['forcelogin'])
		{
			if(isset($survey_detail['mobiles']) && $survey_detail['mobiles'])
			{
				$mobiles = explode("\n", $survey_detail['mobiles']);
				if(!in_array(\dash\user::detail('mobile'), $mobiles))
				{
					\dash\notif::error(T_("This survey was limited to some mobile and your mobile is not in this list"));
					return false;
				}
			}
		}

		// can not update answer - get from setting
		$cannotupdateanswer = false;
		if(isset($survey_detail['setting']['cannotupdateanswer']) && $survey_detail['setting']['cannotupdateanswer'])
		{
			$cannotupdateanswer = true;
		}

		// if user try to update answer and the update answer is locked this var is true
		$user_try_to_update = false;

		// load question detail
		$question_detail = \lib\db\questions::get(['survey_id' => $survey_id, 'id' => $question_id, 'limit' => 1]);

		if(!$question_detail || !isset($question_detail['id']))
		{
			\dash\notif::error(T_("Invalid question id"));
			return false;
		}
		// get the step from sort field
		// in random mode this step must be get from answers.questions field
		// @check @reza in result show to true result
		$step = 1;
		if(array_key_exists('sort', $question_detail))
		{
			$step = intval($question_detail['sort']);
		}

		$question_id = $question_detail['id'];

		$question_detail = \lib\app\question::ready($question_detail);

		// set valiable
		\dash\app::variable($_args);

		// get answer and skip
		$answer = \dash\app::request('answer');
		$skip   = \dash\app::request('skip') ? true : false;
		if($skip)
		{
			$answer = null;
		}

		// check required password question
		if(isset($question_detail['type']) && $question_detail['type'] === 'password')
		{
			if($skip || !isset($answer))
			{
				\dash\notif::error(T_("Please fill the password"), 'answer');
				return false;
			}
		}
		// if user not skip question check validate answer
		if(!$skip)
		{
			$validation = self::answer_validate($question_detail, $answer);
			// invalid answer must be return
			// the notif of this error make in self::answer_validate() and in this place just return false
			if(!$validation)
			{
				return false;
			}
		}

		// check if this qustion is required and user not answered to it make an error
		$require = self::check_require($question_detail, $answer, $skip);
		if(!$require)
		{
			\dash\notif::error(T_("Please fill this field to continue"), 'answer');
			return false;
		}

		// get the real answer title
		// this variable maked in self::answer_validate()
		if(\dash\temp::get('realAnswerTitle'))
		{
			$answer = \dash\temp::get('realAnswerTitle');
		}

		$answer_term_id  = null;
		$multiple_choice = false;

		// get the answer term id. not in multichoice mode
		if(!$skip)
		{
			if(is_array($answer))
			{
				$multiple_choice = true;
			}

			if(($answer || $answer === '0') && !$multiple_choice)
			{
				$answer_term_id = \lib\db\answerterms::get_id($answer, $question_detail['type']);
				if(!$answer_term_id)
				{
					\dash\notif::error(T_("No way to inset your answer"));
					return false;
				}
			}
		}

		// get the old answer
		$load_old_answer =
		[
			'user_id'   => \dash\user::id(),
			'survey_id' => $survey_id,
			'limit'     => 1,
		];

		$load_old_answer = \lib\db\answers::get($load_old_answer);

		// get step in random mode
		if(isset($load_old_answer['questions']) && is_string($load_old_answer['questions']))
		{
			// just if the random question is on
			// maybe the user run survey and after answering set randomquestin is off!
			if(isset($survey_detail['setting']['randomquestion']) && $survey_detail['setting']['randomquestion'])
			{
				$questions_json = json_decode($load_old_answer['questions'], true);
				if(is_array($questions_json))
				{
					foreach ($questions_json as $json_step => $value)
					{
						if(isset($value['question_id']) && intval($value['question_id']) === intval($question_id))
						{
							$step = $json_step;
							break;
						}
					}
				}
			}
		}

		// get count block of this survey
		$countblock          = (isset($survey_detail['countblock']) && $survey_detail['countblock']) ? intval($survey_detail['countblock']) : 0;
		$update_answer       = [];
		$force_update_answer = [];

		if(!$load_old_answer)
		{
			$insert_answer =
			[
				'user_id'      => \dash\user::id(),
				'survey_id'    => $survey_id,
				'startdate'    => self::dateNow(),
				'step'         => 1,
				'lastquestion' => $question_id,
				'status'       => 'start',
				'ref'          => null,
				'skip'         => $skip   ? 1 : null,
				'skiptry'      => $skip   ? 1 : null,
				'answer'       => $answer && !$skip ? 1 : null,
				'answertry'    => $answer && !$skip ? 1 : null,
			];
			$answer_id = \lib\db\answers::insert($insert_answer);
		}
		else
		{

			$answer_id       = $load_old_answer['id'];
			$answer_count    = (isset($load_old_answer['answer']) && $load_old_answer['answer'])            ? intval($load_old_answer['answer'])    	: 0;
			$skip_count      = (isset($load_old_answer['skip']) && $load_old_answer['skip'])           		? intval($load_old_answer['skip'])      	: 0;
			$answertry_count = (isset($load_old_answer['answertry']) && $load_old_answer['answertry']) 		? intval($load_old_answer['answertry']) 	: 0;
			$skiptry_count   = (isset($load_old_answer['skiptry']) && $load_old_answer['skiptry'])     		? intval($load_old_answer['skiptry'])   	: 0;

			if($skip)
			{
				$update_answer['skip']    = $skip_count + 1;
				$update_answer['skiptry'] = $skiptry_count + 1;
			}

			if($answer)
			{
				$update_answer['answer']    = $answer_count + 1;
				$update_answer['answertry'] = $answertry_count + 1;
			}

			$update_answer['step']         = $step;
			$force_update_answer['step']   = $step;
			$update_answer['lastquestion'] = $question_id;
			$update_answer['lastmodified'] = self::dateNow();

		}
		// get date loaded set in session
		$time_key = 'dateview_'. (string) $survey_id. '_'. (string) $step;
		$dateview = \dash\session::get($time_key) && is_string(\dash\session::get($time_key)) ? \dash\session::get($time_key) : self::dateNow();

		// get old answer detail
		$old_answer_detail_args =
		[
			'user_id'     => \dash\user::id(),
			'survey_id'   => $survey_id,
			'answer_id'   => $answer_id,
			'question_id' => $question_id,
			'limit'       => 1,
		];

		$old_answer_detail = \lib\db\answerdetails::get($old_answer_detail_args);

		if(isset($old_answer_detail['dateview']))
		{
			$dateview = $old_answer_detail['dateview'];
		}

		// check schedule timing limit of answer to survey or question
		$check_schedule = self::check_schedule($survey_detail, $question_detail, $load_old_answer, $dateview);

		if($check_schedule)
		{
			return \lib\app\analyze::question_step($check_schedule, $step, $survey_detail, \dash\user::id());
		}


		// if(intval($step) === intval($countblock) || intval($countblock) === 1)
		if(self::check_complete($step, $survey_detail, \dash\user::id()))
		{
			$check_require_is_answer = \lib\db\answers::required_question_is_answered($survey_id, \dash\user::id());
			if($check_require_is_answer === true)
			{
				$update_answer['complete'] = 1;
				$update_answer['enddate']  = self::dateNow();
			}
			else
			{
				$update_answer['complete'] = 0;
				$update_answer['enddate']  = null;

				\dash\temp::set('notAnsweredQuestion', $check_require_is_answer);

				$msg = T_("You not answer to some required question"). ' '. T_("Your survey is not complete");

				if(isset($check_require_is_answer[0]['sort']))
				{
					$msg = "<a href='". \dash\url::kingdom(). '/s/'. \dash\coding::encode($survey_id). '?step='. $check_require_is_answer[0]['sort']. "'>$msg</a>";
				}

				if(\dash\url::content() !== 'hook')
				{
					\dash\notif::warn($msg);
				}
			}
		}

		if(!empty($update_answer))
		{
			if(!$user_try_to_update)
			{
				\lib\db\answers::update($update_answer, $answer_id);
			}
			else
			{
				if(!empty($force_update_answer))
				{
					\lib\db\answers::update($force_update_answer, $answer_id);
				}

			}
		}

		$old_answer_detail_args =
		[
			'user_id'     => \dash\user::id(),
			'survey_id'   => $survey_id,
			'answer_id'   => $answer_id,
			'question_id' => $question_id,
		];


		if(!$multiple_choice || $skip)
		{
			$old_answer_detail_args['limit'] = 1;
			$old_answer_detail = \lib\db\answerdetails::get($old_answer_detail_args);
			if(isset($old_answer_detail['id']))
			{
				$update_answer_detail =
				[
					'answerterm_id' => $answer_term_id,
					'skip'          => $skip ? 1 : null,
					'dateanswer'    => self::dateNow(),
					'score'         => self::$answer_score,
				];

				if(!$cannotupdateanswer)
				{
					\lib\db\answerdetails::update($update_answer_detail, $old_answer_detail['id']);
				}
				else
				{
					$user_try_to_update = true;

				}
			}
			else
			{
				$insert_answer_detail =
				[
					'user_id'       => \dash\user::id(),
					'survey_id'     => $survey_id,
					'answer_id'     => $answer_id,
					'question_id'   => $question_id,
					'answerterm_id' => $answer_term_id,
					'skip'          => $skip ? 1 : null,
					'dateview'      => $dateview,
					'dateanswer'    => self::dateNow(),
					'score'         => self::$answer_score,
				];

				\lib\db\answerdetails::insert($insert_answer_detail);
			}
		}
		else
		{
			// mutli choise mode
			$old_answer_detail = \lib\db\answerdetails::get($old_answer_detail_args);
			if($old_answer_detail && $cannotupdateanswer)
			{
				$user_try_to_update = true;
			}
			else
			{
				if($old_answer_detail)
				{
					\lib\db\answerdetails::delete_where($old_answer_detail_args);
				}

				// insert new answer detail
				$multi_insert = [];
				foreach ($answer as $key => $value)
				{
					$answer_term_id = \lib\db\answerterms::get_id($value, $question_detail['type']);
					$multi_insert[] =
					[
						'user_id'       => \dash\user::id(),
						'survey_id'     => $survey_id,
						'answer_id'     => $answer_id,
						'question_id'   => $question_id,
						'answerterm_id' => $answer_term_id,
						'dateview'      => $dateview,
						'dateanswer'    => self::dateNow(),
						'score'         => isset(self::$answer_score_multi[$value]) ? self::$answer_score_multi[$value] : null,
					];
				}

				if(!empty($multi_insert))
				{
					\lib\db\answerdetails::multi_insert($multi_insert);
				}
			}
		}

		if($user_try_to_update && $cannotupdateanswer)
		{
			\dash\notif::warn(T_("You can not update your answer"));
		}

		return \lib\app\analyze::question_step('answer', $step, $survey_detail, \dash\user::id());
	}


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


	private static function check_schedule($_survey_detail, $_question_detail, $_answer_detail, $_dateview)
	{
		// the user not answer to this survey yet
		if(!$_answer_detail)
		{
			return false;
		}

		// check enable schedule timing of this survey
		if(isset($_survey_detail['setting']['schedule']['status']) && $_survey_detail['setting']['schedule']['status'])
		{
			$surveytime   = isset($_survey_detail['setting']['schedule']['surveytime']) 	? $_survey_detail['setting']['schedule']['surveytime'] 	 : 0;
			$questiontime = isset($_survey_detail['setting']['schedule']['questiontime']) 	? $_survey_detail['setting']['schedule']['questiontime'] : 0;
			$surveytime   = intval($surveytime) * 60;
			$questiontime = intval($questiontime) * 60;
			$start_survey = null;
			$now          = time();
			$dateview     = strtotime($_dateview);

			if(isset($_answer_detail['startdate']))
			{
				$start_survey = strtotime($_answer_detail['startdate']);
			}

			if($surveytime && $start_survey)
			{
				if($now - $start_survey > $surveytime)
				{
					\dash\notif::error(T_("Your answer time ended for this survey"));
					return 'surveytime';
				}
			}

			if($questiontime && $dateview)
			{
				if($now - $dateview > $questiontime)
				{
					\dash\notif::error(T_("Your answer time ended for this question"));
					return 'questiontime';
				}
			}

		}
		return false;
	}


	public static function check_require($_question_detail, $_answer, $_skip = false)
	{
		// check is require
		if(isset($_question_detail['require']) && $_question_detail['require'])
		{
			if((!$_answer && $_answer !== '0') || (is_array($_answer) && empty($_answer)))
			{
				if(isset($_question_detail['type']) && $_question_detail['type'] === 'confirm')
				{
					return true;
				}

				if(!$_skip)
				{
					return false;
				}
			}
		}
		return true;
	}



	public static function answer_validate($_question_detail, $_answer)
	{
		$myType = null;

		if(isset($_question_detail['type']))
		{
			$myType = $_question_detail['type'];
		}

		$default = \lib\app\question::get_type($myType, 'default_load');
		$min    = 0;
		if(isset($default['min']))
		{
			$min = $default['min'];
		}

		$max = 1E+9;

		if(isset($default['max']))
		{
			$max = $default['max'];
		}

		if(isset($_question_detail['setting'][$myType]['min']))
		{
			$min = intval($_question_detail['setting'][$myType]['min']);
		}

		if(isset($_question_detail['setting'][$myType]['max']))
		{
			$max = intval($_question_detail['setting'][$myType]['max']);
		}

		$valid = true;
		switch ($_question_detail['type'])
		{
			case 'short_answer':
			case 'descriptive_answer':
				if(!is_string($_answer))
				{
					\dash\notif::error(T_("Invalid answer"), 'answer');
					$valid = false;
				}
				if($_answer)
				{
					if(mb_strlen($_answer) < $min || mb_strlen($_answer) > $max)
					{
						\dash\notif::error(T_("Your answer is out of range"), 'answer');
						$valid = false;
					}
				}
				break;

			case 'numeric':
			case 'rating':
			case 'rangeslider':
				$_answer = \dash\utility\convert::to_en_number($_answer);
				\dash\temp::set('realAnswerTitle',$_answer);

				if($_answer)
				{
					if(!is_numeric($_answer))
					{
						$valid = false;
					}

					if(intval($_answer) < $min || intval($_answer) > $max)
					{
						\dash\notif::error(T_("Your answer is out of range"), 'answer');
						$valid = false;
					}
				}
				break;

			case 'single_choice':
			case 'dropdown':
				if($_answer)
				{
					if(isset($_question_detail['choice']) && is_array($_question_detail['choice']))
					{
						$choice_title = array_column($_question_detail['choice'], 'id');
						if(!in_array($_answer, $choice_title))
						{
							if(\dash\permission::supervisor())
							{
								\dash\notif::error(T_("This choice not found in choice list!"). ' _ '.$_answer . ' _ '. @$_question_detail['title'], 'answer');
							}
							else
							{
								\dash\notif::error(T_("This choice not found in choice list!"), 'answer');
							}
							$valid = false;
						}
						else
						{
							foreach ($_question_detail['choice'] as $answerList)
							{
								if(isset($answerList['id']) && intval($_answer) === intval($answerList['id']))
								{
									$myKey = $answerList['id'];

									if(array_key_exists('title', $answerList))
									{
										\dash\temp::set('realAnswerTitle', $answerList['title']);
									}

									if(array_key_exists('score', $answerList))
									{
										self::$answer_score = intval($answerList['score']);

									}
								}
							}
						}
					}
				}
				break;

			case 'multiple_choice':
				if(is_array($_answer) && isset($_question_detail['choice']) && is_array($_question_detail['choice']))
				{
					$choice_title = array_column($_question_detail['choice'], 'id');
					foreach ($_answer as $key => $value)
					{
						if(!in_array($value, $choice_title))
						{
							\dash\notif::error(T_("This choice not found in choice list!"), 'answer');
							$valid = false;
						}
					}

					if(count($_answer) < $min || count($_answer) > $max)
					{
						\dash\notif::error(T_("Your can choose between :min and :max option", ['min' => \dash\utility\human::fitNumber($min), 'max' => \dash\utility\human::fitNumber($max)]), 'answer');
						$valid = false;
					}

					$realAnswerTitle = [];

					foreach ($_answer as $key => $value)
					{
						foreach ($_question_detail['choice'] as $id_answer => $choise_detail)
						{
							if(isset($choise_detail['id']) && intval($choise_detail['id']) === intval($value) && array_key_exists('title', $choise_detail))
							{
								array_push($realAnswerTitle, $_question_detail['choice'][$id_answer]['title']);
							}

							if(isset($_question_detail['choice'][$id_answer]['score']))
							{
								self::$answer_score_multi[$_question_detail['choice'][$id_answer]['title']] = intval($_question_detail['choice'][$id_answer]['score']);
							}
						}
					}

					\dash\temp::set('realAnswerTitle', $realAnswerTitle);
				}
				break;

			case 'date':
				$_answer = \dash\utility\convert::to_en_number($_answer);

				$_answer = \dash\date::db($_answer);

				if($_answer === false)
				{
					\dash\notif::error(T_("Invalid date"), 'answer');
					$valid = false;
				}

				$_answer = \dash\date::force_gregorian($_answer);

				\dash\temp::set('realAnswerTitle',$_answer);
				break;

			case 'time':
				$_answer = \dash\utility\convert::to_en_number($_answer);
				\dash\temp::set('realAnswerTitle',$_answer);
				if(\dash\date::make_time(\dash\utility\convert::to_en_number($_answer)) === false)
				{
					\dash\notif::error(T_("Invalid time"), 'answer');
					$valid = false;
				}
				break;

			case 'email':
				$_answer = \dash\utility\convert::to_en_number($_answer);
				\dash\temp::set('realAnswerTitle',$_answer);
				if($_answer && !filter_var($_answer, FILTER_VALIDATE_EMAIL))
				{
					\dash\notif::error(T_("Invalid email"), 'answer');
					$valid = false;
				}
				break;

			case 'mobile':
				$_answer = \dash\utility\convert::to_en_number($_answer);
				\dash\temp::set('realAnswerTitle',$_answer);
				if($_answer && !\dash\utility\filter::mobile(\dash\utility\convert::to_en_number($_answer)))
				{
					\dash\notif::error(T_("Invalid mobile"), 'answer');
					$valid = false;
				}
				break;

			case 'website':

				if(substr($_answer, 0, 4) !== 'http')
				{
					$_answer = 'http://'. $_answer;
				}

				if($_answer && !filter_var($_answer, FILTER_VALIDATE_URL))
				{
					\dash\notif::error(T_("Invalid url"), 'answer');
					$valid = false;
				}
				break;

			case 'password':
				if(isset($_question_detail['setting']['password']['password']))
				{
					if($_question_detail['setting']['password']['password'] == $_answer)
					{
						$valid = true;
					}
					else
					{
						$valid = false;
					}
				}

				if(!$valid)
				{
					\dash\notif::error(T_("Invalid password"), 'answer');
				}
				break;
		}



		return $valid;
	}


	/**
	 * ready data of question to load in api
	 *
	 * @param      <type>  $_data  The data
	 */
	public static function ready($_data, $_meta = [])
	{
		$result    = [];
		$startdate = null;
		$enddate   = null;

		$score = [];
		if(isset($_meta['score']) && is_array($_meta['score']))
		{
			$score = $_meta['score'];
		}

		foreach ($_data as $key => $value)
		{
			switch ($key)
			{
				case 'id':
				case 'survey_id':
				case 'question_id':
					$result[$key] = \dash\coding::encode($value);
					break;

				case 'user_id':
					$result[$key] = \dash\coding::encode($value);
					if(isset($score[$value]['score']))
					{
						$result['score'] = $score[$value]['score'];
					}
					else
					{
						$result['score'] = null;
					}
					break;

				case 'startdate':
					$result[$key] = $value;
					$startdate = strtotime($value);
					break;

				case 'enddate':
					$result[$key] = $value;
					$enddate = strtotime($value);
					break;

				default:
					$result[$key] = $value;
					break;
			}
		}

		if($startdate && $enddate)
		{
			$result['answer_in'] = \dash\utility\human::time($enddate - $startdate, null, null, 'sec');
		}

		return $result;
	}


	private static function check_complete($_step, $_survey_detail, $_user_id)
	{
		$result = \lib\app\analyze::question_step('is_complete', $_step, $_survey_detail, $_user_id);

		return $result;
	}
}
?>