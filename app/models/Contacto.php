<?php

namespace diag\cc;

class Contacto extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id_contacto", type="integer", length=11, nullable=false)
     */
    protected $id_contacto;

    /**
     *
     * @var string
     * @Column(column="documento", type="string", length=12, nullable=false)
     */
    protected $documento;

    /**
     *
     * @var string
     * @Column(column="nombre", type="string", length=20, nullable=false)
     */
    protected $nombre;

    /**
     *
     * @var string
     * @Column(column="p_apellido", type="string", length=20, nullable=false)
     */
    protected $p_apellido;

    /**
     *
     * @var string
     * @Column(column="s_apellido", type="string", length=20, nullable=true)
     */
    protected $s_apellido;

    /**
     *
     * @var string
     * @Column(column="tipo_doc", type="string", length=2, nullable=true)
     */
    protected $tipo_doc;

    /**
     *
     * @var string
     * @Column(column="correo", type="string", length=50, nullable=true)
     */
    protected $correo;

    /**
     *
     * @var string
     * @Column(column="direccion", type="string", length=100, nullable=true)
     */
    protected $direccion;

    /**
     *
     * @var string
     * @Column(column="pais", type="string", length=15, nullable=false)
     */
    protected $pais;

    /**
     *
     * @var string
     * @Column(column="depto", type="string", length=15, nullable=true)
     */
    protected $depto;

    /**
     *
     * @var string
     * @Column(column="mcipio", type="string", length=15, nullable=true)
     */
    protected $mcipio;

    /**
     *
     * @var string
     * @Column(column="celular", type="string", length=12, nullable=false)
     */
    protected $celular;

    /**
     *
     * @var string
     * @Column(column="fijo", type="string", length=15, nullable=true)
     */
    protected $fijo;

    /**
     *
     * @var string
     * @Column(column="genero", type="string", length=1, nullable=false)
     */
    protected $genero;

    /**
     *
     * @var string
     * @Column(column="fec_nacimiento", type="string", nullable=false)
     */
    protected $fec_nacimiento;

    /**
     *
     * @var string
     * @Column(column="nivel_estudio", type="string", length=1, nullable=false)
     */
    protected $nivel_estudio;

    /**
     *
     * @var string
     * @Column(column="ocupacion", type="string", length=100, nullable=true)
     */
    protected $ocupacion;

    /**
     *
     * @var string
     * @Column(column="cargo", type="string", length=20, nullable=false)
     */
    protected $cargo;

    /**
     *
     * @var integer
     * @Column(column="id_usuario", type="integer", length=11, nullable=true)
     */
    protected $id_usuario;

    /**
     *
     * @var integer
     * @Column(column="id_empresa", type="integer", length=11, nullable=false)
     */
    protected $id_empresa;

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
     * Method to set the value of field documento
     *
     * @param string $documento
     * @return $this
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;

        return $this;
    }

    /**
     * Method to set the value of field nombre
     *
     * @param string $nombre
     * @return $this
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Method to set the value of field p_apellido
     *
     * @param string $p_apellido
     * @return $this
     */
    public function setPApellido($p_apellido)
    {
        $this->p_apellido = $p_apellido;

        return $this;
    }

    /**
     * Method to set the value of field s_apellido
     *
     * @param string $s_apellido
     * @return $this
     */
    public function setSApellido($s_apellido)
    {
        $this->s_apellido = $s_apellido;

        return $this;
    }

    /**
     * Method to set the value of field tipo_doc
     *
     * @param string $tipo_doc
     * @return $this
     */
    public function setTipoDoc($tipo_doc)
    {
        $this->tipo_doc = $tipo_doc;

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
     * Method to set the value of field direccion
     *
     * @param string $direccion
     * @return $this
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Method to set the value of field pais
     *
     * @param string $pais
     * @return $this
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Method to set the value of field depto
     *
     * @param string $depto
     * @return $this
     */
    public function setDepto($depto)
    {
        $this->depto = $depto;

        return $this;
    }

    /**
     * Method to set the value of field mcipio
     *
     * @param string $mcipio
     * @return $this
     */
    public function setMcipio($mcipio)
    {
        $this->mcipio = $mcipio;

        return $this;
    }

    /**
     * Method to set the value of field celular
     *
     * @param string $celular
     * @return $this
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Method to set the value of field fijo
     *
     * @param string $fijo
     * @return $this
     */
    public function setFijo($fijo)
    {
        $this->fijo = $fijo;

        return $this;
    }

    /**
     * Method to set the value of field genero
     *
     * @param string $genero
     * @return $this
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Method to set the value of field fec_nacimiento
     *
     * @param string $fec_nacimiento
     * @return $this
     */
    public function setFecNacimiento($fec_nacimiento)
    {
        $this->fec_nacimiento = $fec_nacimiento;

        return $this;
    }

    /**
     * Method to set the value of field nivel_estudio
     *
     * @param string $nivel_estudio
     * @return $this
     */
    public function setNivelEstudio($nivel_estudio)
    {
        $this->nivel_estudio = $nivel_estudio;

        return $this;
    }

    /**
     * Method to set the value of field ocupacion
     *
     * @param string $ocupacion
     * @return $this
     */
    public function setOcupacion($ocupacion)
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    /**
     * Method to set the value of field cargo
     *
     * @param string $cargo
     * @return $this
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

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
     * Returns the value of field id_contacto
     *
     * @return integer
     */
    public function getIdContacto()
    {
        return $this->id_contacto;
    }

    /**
     * Returns the value of field documento
     *
     * @return string
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * Returns the value of field nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Returns the value of field p_apellido
     *
     * @return string
     */
    public function getPApellido()
    {
        return $this->p_apellido;
    }

    /**
     * Returns the value of field s_apellido
     *
     * @return string
     */
    public function getSApellido()
    {
        return $this->s_apellido;
    }

    /**
     * Returns the value of field tipo_doc
     *
     * @return string
     */
    public function getTipoDoc()
    {
        return $this->tipo_doc;
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
     * Returns the value of field direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Returns the value of field pais
     *
     * @return string
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Returns the value of field depto
     *
     * @return string
     */
    public function getDepto()
    {
        return $this->depto;
    }

    /**
     * Returns the value of field mcipio
     *
     * @return string
     */
    public function getMcipio()
    {
        return $this->mcipio;
    }

    /**
     * Returns the value of field celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Returns the value of field fijo
     *
     * @return string
     */
    public function getFijo()
    {
        return $this->fijo;
    }

    /**
     * Returns the value of field genero
     *
     * @return string
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Returns the value of field fec_nacimiento
     *
     * @return string
     */
    public function getFecNacimiento()
    {
        return $this->fec_nacimiento;
    }

    /**
     * Returns the value of field nivel_estudio
     *
     * @return string
     */
    public function getNivelEstudio()
    {
        return $this->nivel_estudio;
    }

    /**
     * Returns the value of field ocupacion
     *
     * @return string
     */
    public function getOcupacion()
    {
        return $this->ocupacion;
    }

    /**
     * Returns the value of field cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
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
     * Returns the value of field id_empresa
     *
     * @return integer
     */
    public function getIdEmpresa()
    {
        return $this->id_empresa;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bd_diagnostico");
        $this->setSource("contacto");
        $this->hasMany('id_contacto', 'diag\cc\Usuario', 'id_contacto', ['alias' => 'Usuario']);
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
        return 'contacto';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contacto[]|Contacto|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Contacto|\Phalcon\Mvc\Model\ResultInterface
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
            'id_contacto' => 'id_contacto',
            'documento' => 'documento',
            'nombre' => 'nombre',
            'p_apellido' => 'p_apellido',
            's_apellido' => 's_apellido',
            'tipo_doc' => 'tipo_doc',
            'correo' => 'correo',
            'direccion' => 'direccion',
            'pais' => 'pais',
            'depto' => 'depto',
            'mcipio' => 'mcipio',
            'celular' => 'celular',
            'fijo' => 'fijo',
            'genero' => 'genero',
            'fec_nacimiento' => 'fec_nacimiento',
            'nivel_estudio' => 'nivel_estudio',
            'ocupacion' => 'ocupacion',
            'cargo' => 'cargo',
            'id_usuario' => 'id_usuario',
            'id_empresa' => 'id_empresa'
        ];
    }

}
