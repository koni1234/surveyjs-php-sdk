<?php


namespace SurveyJsPhpSdk\Parser;


use SurveyJsPhpSdk\Exception\InvalidSurveyResultException;
use SurveyJsPhpSdk\Model\Element\AbstractSurveyElementModel;
use SurveyJsPhpSdk\Model\SurveyElementModel;
use SurveyJsPhpSdk\Model\SurveyPageModel;
use SurveyJsPhpSdk\Model\SurveyResultModel;
use SurveyJsPhpSdk\Model\SurveyTemplateModel;

class SurveyResultParser
{

    /**
     * @param SurveyTemplateModel $survey
     * @param string $jsonResults
     *
     * @throws InvalidSurveyResultException
     *
     * @return SurveyResultModel[]
     */
    public static function parseToModel(SurveyTemplateModel $survey, string $jsonResults): array
    {
        $resultsModels = [];

        $results = (array)json_decode($jsonResults);

        foreach($results as $question => $result){
            $resultModel = new SurveyResultModel();

            $resultModel->setQuestion($question);
            $resultModel->setAnswer($result);

            if(!self::validateResult($survey->getPages(), $resultModel)){
                throw new InvalidSurveyResultException();
            }

            $resultsModels[] = $resultModel;
        }

        return $resultsModels;
    }

    /**
     * @param SurveyPageModel[] $pages
     * @param SurveyResultModel $result
     *
     * @return bool
     */
    private static function validateResult(array $pages, SurveyResultModel $result): bool
    {
        /** @var SurveyPageModel $page */
        foreach($pages as $page){
            /** @var AbstractSurveyElementModel $element */
            foreach($page->getElements() as $element){
                if($element->isValidResult($result)){
                    return true;
                }
            }
        }

        return false;
    }
}