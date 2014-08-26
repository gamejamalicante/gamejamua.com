<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\CompoBundle\Form\Type;

use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    protected $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message', 'textarea');

        if (!$this->user) {
            $builder->add('email');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_compo_contact';
    }
}
