<?php
/**
 * Created by PhpStorm.
 * User: rvelhote
 * Date: 11/2/16
 * Time: 10:55 PM
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Rule;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRulesData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $moves = [];
        foreach(['rock', 'paper', 'scissors'] as $move) {
            $moves[] = $this->getReference(sprintf('rps-move-%s', $move));
        }

        $gameType = $this->getReference('rps-game-type');

        $rule1 = new Rule();
        $rule1->setGameType($gameType);
        $rule1->setWinner($moves[1]);
        $rule1->setLoser($moves[0]);
        $rule1->setOutcome('Covers');

        $rule2 = new Rule();
        $rule2->setGameType($gameType);
        $rule2->setWinner($moves[2]);
        $rule2->setLoser($moves[1]);
        $rule2->setOutcome('Cuts');

        $rule3 = new Rule();
        $rule3->setGameType($gameType);
        $rule3->setWinner($moves[0]);
        $rule3->setLoser($moves[2]);
        $rule3->setOutcome('Smashes');

        $gameType->addRule($rule1);
        $gameType->addRule($rule2);
        $gameType->addRule($rule3);

        $manager->persist($gameType);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}