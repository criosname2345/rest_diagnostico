<?php

namespace diag\cc;

class Usuario extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id_usuario", type="integer", length=11, nullable=false)
     */
    protected $id_usuario;

    /**
     *
     * @var string
     * @Column(column="correo", type="string", length=50, nullable=false)
     */
    protected $correo;

    /**
     *
     * @var string
     * @Column(column="contrasena", type="string", length=100, nullable=false)
     */
    protected $contrasena;

    /**
     *
     * @var integer
     * @Column(column="id_contacto", type="integer", length=11, nullable=true)
     */
    protected $id_contacto;

    /**
     *
     * @var integer
     * @Column(column="id_rol", type="integer", length=11, nullable=false)
     */
    protected $id_rol;

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
     * Method to set the value of field correo
     *
     * @param string $correo
     * @return $this
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Method to set the value of field contrasena
     *
     * @param string $contrasena
     * @return $this
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    /**
     * Method to set the value of field id_contacto
     *
     * @param integer $id_contacto
     * @return $this
     */
    public function setIdContacto($id_contacto)
    {
        $this->id_contacto = $id_contacto;

        return $this;
    }

    /**
     * Method to set the value of field id_rol
     *
     * @param integer $id_rol
     * @return $this
     */
    public function setIdRol($id_rol)
    {
        $this->id_rol = $id_rol;

        return $this;
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
     * Returns the value of field correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Returns the value of field contrasena
     *
     * @return string
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Returns the value of field id_contacto
     *
     * @return integer
     */
    public function getIdContacto()
    {
        return $this->id_contacto;
    }

    /**
     * Returns the value of field id_rol
     *
     * @return integer
     */
    public function getIdRol()
    {
        return $this->id_rol;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("usuario");
        $this->hasMany('id_usuario', 'diag\cc\Contacto', 'id_usuario', ['alias' => 'Contacto']);
        $this->hasMany('id_usuario', 'diag\cc\Visita', 'id_usuario', ['alias' => 'Visita']);
        $this->belongsTo('id_rol', 'diag\cc\Rol', 'id_rol', ['alias' => 'Rol']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usuario';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuario[]|Usuario|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuario|\Phalcon\Mvc\Model\ResultInterface
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
            'id_usuario' => 'id_usuario',
            'correo' => 'correo',
            'contrasena' => 'contrasena',
            'id_contacto' => 'id_contacto',
            'id_rol' => 'id_rol'
        ];
    }

}
