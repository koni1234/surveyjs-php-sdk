<?php

namespace SurveyJsPhpSdk\Factory;

use SurveyJsPhpSdk\Configuration\ElementConfigurationInterface;
use SurveyJsPhpSdk\Exception\ElementConfigurationErrorException;
use SurveyJsPhpSdk\Exception\MissingElementConfigurationException;
use SurveyJsPhpSdk\Model\Element\ElementInterface;
use SurveyJsPhpSdk\Parser\Element\CheckboxElementParser;
use SurveyJsPhpSdk\Parser\Element\CommentElementParser;
use SurveyJsPhpSdk\Parser\Element\RadioGroupElementParser;
use SurveyJsPhpSdk\Parser\Element\RatingElementParser;

class ElementFactory
{
    public const CHECKBOX_TYPE = 'checkbox';
    public const COMMENT_TYPE = 'comment';
    public const RADIO_GROUP_TYPE = 'radiogroup';
    public const RATING_TYPE = 'rating';

    public const KNOWN_TYPES = [
        self::COMMENT_TYPE,
        self::CHECKBOX_TYPE,
        self::RADIO_GROUP_TYPE,
        self::RATING_TYPE
    ];

    /**
     * @param \stdClass $element
     * @param ElementConfigurationInterface|null $configuration
     *
     * @throws ElementConfigurationErrorException
     * @throws MissingElementConfigurationException
     *
     * @return ElementInterface
     */
    public static function create(\stdClass $element, ?ElementConfigurationInterface $configuration): ElementInterface
    {
        switch ($element->type) {
            case self::CHECKBOX_TYPE:
                $parser = new CheckboxElementParser();
                return $parser->parse($element);
            case self::COMMENT_TYPE:
                $parser = new CommentElementParser();
                return $parser->parse($element);
            case self::RADIO_GROUP_TYPE:
                $parser = new RadioGroupElementParser();
                return $parser->parse($element);
            case self::RATING_TYPE:
                $parser = new RatingElementParser();
                return $parser->parse($element);
            default:
                if ($element->type === $configuration->getType()) {
                    //checking that custom element parser returns the correct custom element model as in configuration
                    if (get_class($model = $configuration->getParser()->parse($element)) !== get_class($configuration->getElement())) {
                        throw new ElementConfigurationErrorException('Configured model does not correspond to model returned by parser in configuration for type: ' . $configuration->getType());
                    }

                    return $model;
                }
                throw new MissingElementConfigurationException($element->type);
        }
    }
}
