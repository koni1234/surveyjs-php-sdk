<?php


namespace SurveyJsPhpSdk\Tests\Parser\Element;


use PHPUnit\Framework\TestCase;
use SurveyJsPhpSdk\Factory\ElementFactory;
use SurveyJsPhpSdk\Model\ChoiceModel;
use SurveyJsPhpSdk\Model\Element\RatingElement;
use SurveyJsPhpSdk\Parser\Element\RatingElementParser;

class RatingElementParserTest extends TestCase
{

    /**
     * @var array
     */
    private $elements = [];
    /**
     * @var RatingElementParser
     */
    private $sut;

    protected function setUp()
    {
        $choice = (object)[
            'text'  => '6',
            'value' => '6'
        ];

        $this->elements[] = (object)[
            'type'         => ElementFactory::RATING_TYPE,
            'name'         => 'element_1',
            'title'        => 'Element 1',
            'isRequired'   => true,
            'enableIf'     => 'plausible conditions',
            'rateMax'      => 5
        ];

        $this->elements[] = (object)[
            'type'         => ElementFactory::RATING_TYPE,
            'name'         => 'element_2',
            'title'        => 'Element 2',
            'isRequired'   => false,
            'enableIf'     => 'implausible conditions',
            'rateValues'   => [
                '1',
                '2',
                '3',
                '4',
                '5',
                $choice
            ],
            'rateMax'      => 6
        ];

        $this->sut = new RatingElementParser();
    }

    public function testParseSuccess()
    {
        foreach($this->elements as $element){
            $model = $this->sut->parse($element);

            $this->assertInstanceOf(RatingElement::class, $model);
            $this->assertEquals($element->rateMax, count($model->getChoices()));
            $this->assertEquals($element->name, $model->getName());
            $this->assertEquals($element->title, $model->getTitle());
            $this->assertEquals($element->isRequired, $model->isRequired());
            $this->assertEquals($element->enableIf, $model->getEnableIf());

            foreach ($model->getChoices() as $index => $choice){
                $this->assertInstanceOf(ChoiceModel::class, $choice);
                $this->assertEquals((string)($index + 1), $choice->getValue());
                $this->assertEquals((string)($index + 1), $choice->getText());
            }
        }
    }
}
