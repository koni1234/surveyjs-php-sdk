<?php

namespace SurveyJsPhpSdk\Model\Element;

use SurveyJsPhpSdk\Model\ResultModel;

class CommentElement extends ElementAbstract
{
    public function isValidResult(ResultModel $result): bool
    {
         return $this->getName() === $result->getQuestion();
    }
}
