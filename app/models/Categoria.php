<?php

namespace diag\cc;

class Categoria extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id_categoria", type="integer", length=11, nullable=false)
     */
    protected $id_categoria;

    /**
     *
     * @var string
     * @Column(column="titulo", type="string", length=50, nullable=false)
     */
    protected $titulo;

    /**
     *
     * @var string
     * @Column(column="descripcion", type="string", length=150, nullable=true)
     */
    protected $descripcion;

    /**
     * Method to set the value of field id_categoria
     *
     * @param integer $id_categoria
     * @return $this
     */
    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;

        return $this;
    }

    /**
     * Method to set the value of field titulo
     *
     * @param string $titulo
     * @return $this
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Method to set the value of field descripcion
     *
     * @param string $descripcion
     * @return $this
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Returns the value of field id_categoria
     *
     * @return integer
     */
    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    /**
     * Returns the value of field titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Returns the value of field descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("categoria");
        $this->hasMany('id_categoria', 'diag\cc\Pregunta', 'id_categoria', ['alias' => 'Pregunta']);
        $this->hasMany('id_categoria', 'diag\cc\Visita', 'id_categoria', ['alias' => 'Visita']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'categoria';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Categoria[]|Categoria|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Categoria|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id_categoria' => 'id_categoria',
            'titulo' => 'titulo',
            'descripcion' => 'descripcion'
        ];
    }

}
