<?php

namespace diag\cc;

class Visita extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id_visita", type="integer", length=11, nullable=false)
     */
    protected $id_visita;

    /**
     *
     * @var string
     * @Column(column="fecha", type="string", nullable=false)
     */
    protected $fecha;

    /**
     *
     * @var string
     * @Column(column="comentario", type="string", length=500, nullable=false)
     */
    protected $comentario;

    /**
     *
     * @var integer
     * @Column(column="id_empresa", type="integer", length=11, nullable=false)
     */
    protected $id_empresa;

    /**
     *
     * @var integer
     * @Column(column="id_usuario", type="integer", length=11, nullable=false)
     */
    protected $id_usuario;

    /**
     *
     * @var integer
     * @Column(column="id_categoria", type="integer", length=11, nullable=true)
     */
    protected $id_categoria;

    /**
     * Method to set the value of field id_visita
     *
     * @param integer $id_visita
     * @return $this
     */
    public function setIdVisita($id_visita)
    {
        $this->id_visita = $id_visita;

        return $this;
    }

    /**
     * Method to set the value of field fecha
     *
     * @param string $fecha
     * @return $this
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Method to set the value of field comentario
     *
     * @param string $comentario
     * @return $this
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Method to set the value of field id_empresa
     *
     * @param integer $id_empresa
     * @return $this
     */
    public function setIdEmpresa($id_empresa)
    {
        $this->id_empresa = $id_empresa;

        return $this;
    }

    /**
     * Method to set the value of field id_usuario
     *
     * @param integer $id_usuario
     * @return $this
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

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
     * Returns the value of field id_visita
     *
     * @return integer
     */
    public function getIdVisita()
    {
        return $this->id_visita;
    }

    /**
     * Returns the value of field fecha
     *
     * @return string
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Returns the value of field comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Returns the value of field id_empresa
     *
     * @return integer
     */
    public function getIdEmpresa()
    {
        return $this->id_empresa;
    }

    /**
     * Returns the value of field id_usuario
     *
     * @return integer
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("visita");
        $this->belongsTo('id_categoria', 'diag\cc\Categoria', 'id_categoria', ['alias' => 'Categoria']);
        $this->belongsTo('id_empresa', 'diag\cc\Empresa', 'id_empresa', ['alias' => 'Empresa']);
        $this->belongsTo('id_usuario', 'diag\cc\Usuario', 'id_usuario', ['alias' => 'Usuario']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'visita';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Visita[]|Visita|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Visita|\Phalcon\Mvc\Model\ResultInterface
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
            'id_visita' => 'id_visita',
            'fecha' => 'fecha',
            'comentario' => 'comentario',
            'id_empresa' => 'id_empresa',
            'id_usuario' => 'id_usuario',
            'id_categoria' => 'id_categoria'
        ];
    }

}
