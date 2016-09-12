<?php
/**
 * Created by PhpStorm.
 * User: rvelhote
 * Date: 9/12/16
 * Time: 10:10 PM
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class GameGuidExists
 * @package AppBundle\Validator
 * @Annotation
 */
class GameGuidExists extends Constraint
{
    public $message = 'The game with %string% does not exist or is no longer playable. Try again.';
}