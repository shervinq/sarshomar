UPDATE
answers
SET
	answers.complete = 1,
	answers.enddate = (SELECT answerdetails.datecreated from answerdetails WHERE answerdetails.answer_id = answers.id limit 1)
WHERE
answers.complete IS NULL AND
answers.enddate IS NULL AND
answers.step   = 1 AND
answers.skip IS NULL AND
answers.answer = 1 AND
answers.datemodified IS NULL AND
answers.survey_id IN (	SELECT 	questions.survey_id	FROM questions GROUP BY questions.survey_id	HAVING COUNT(*) = 1 )

