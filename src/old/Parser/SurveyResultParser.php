<?php


namespace SurveyJsPhpSdk\Parser;


use SurveyJsPhpSdk\Exception\InvalidSurveyResultException;
use SurveyJsPhpSdk\Model\Element\AbstractSurveyElementModel;
use SurveyJsPhpSdk\Model\SurveyElementModel;
use SurveyJsPhpSdk\Model\PageModel;
use SurveyJsPhpSdk\Model\ResultModel;
use SurveyJsPhpSdk\Model\TemplateModel;

class SurveyResultParser
{

    /**
     * @param TemplateModel $survey
     * @param string              $jsonResults
     *
     * @return ResultModel[]
     *@throws InvalidSurveyResultException
     *
     */
    public static function parseToModel(TemplateModel $survey, string $jsonResults): array
    {
        $resultsModels = [];

        $results = (array)json_decode($jsonResults);

        foreach($results as $question => $result){
            $resultModel = new ResultModel();

            $resultModel->setQuestion($question);
            $resultModel->setAnswer($result);

            if(!self::validateResult($survey->getPages(), $resultModel)) {
                throw new InvalidSurveyResultException();
            }

            $resultsModels[] = $resultModel;
        }

        return $resultsModels;
    }

    /**
     * @param PageModel[] $pages
     * @param ResultModel $result
     *
     * @return bool
     */
    private static function validateResult(array $pages, ResultModel $result): bool
    {
        /**
         * @var PageModel $page
        */
        foreach($pages as $page){
            /**
             * @var AbstractSurveyElementModel $element
            */
            foreach($page->getElements() as $element){
                if($element->isValidResult($result)) {
                    return true;
                }
            }
        }

        return false;
    }
}