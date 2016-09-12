<?php
/**
 * Created by PhpStorm.
 * User: rvelhote
 * Date: 9/12/16
 * Time: 10:08 PM
 */

namespace AppBundle\Form;


class MakeMoveForm
{
    /**
     * @AppBundle\Validator\Constraints\GameGuidExists()
     */
    public $game;
    public $move;
}