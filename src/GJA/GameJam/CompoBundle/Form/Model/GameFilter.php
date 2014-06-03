<?php

namespace GJA\GameJam\CompoBundle\Form\Model;

class GameFilter
{
    const FILTER_ALL = "all";
    const FILTER_WINNER = "winner";
    const FILTER_OUT_OF_COMPO = "out_of_compo";

    const ORDER_ALPHA = "alpha";
    const ORDER_LIKES = "likes";

    protected $filterType;

    protected $order;

    protected $compo;

    protected $diversifier;

    /**
     * @param mixed $compo
     */
    public function setCompo($compo)
    {
        $this->compo = $compo;
    }

    /**
     * @return mixed
     */
    public function getCompo()
    {
        return $this->compo;
    }

    /**
     * @param mixed $diversifier
     */
    public function setDiversifier($diversifier)
    {
        $this->diversifier = $diversifier;
    }

    /**
     * @return mixed
     */
    public function getDiversifier()
    {
        return $this->diversifier;
    }

    /**
     * @param mixed $filterType
     */
    public function setFilterType($filterType)
    {
        $this->filterType = $filterType;
    }

    /**
     * @return mixed
     */
    public function getFilterType()
    {
        return $this->filterType;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    public static function getAvailableFilterTypes()
    {
        return [
            self::FILTER_ALL => "Todos los juegos",
            self::FILTER_WINNER => "Premiados",
            self::FILTER_OUT_OF_COMPO => "Fuera de competición"
        ];
    }

    public static function getAvailableOrder()
    {
        return [
            self::ORDER_ALPHA => "Order alfabético",
            self::ORDER_LIKES => "Más votados"
        ];
    }
} 