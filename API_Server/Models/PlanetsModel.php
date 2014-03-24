<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/24/14
 * Time: 9:23 AM
 */
class PlanetModel extends AppModel{
    public $name;
    public $type_id;
    public $diameter;
    public $mass;
    public $rings;
    public $position;

    public function toArray(){
        return array(
            'name' => $this->name,
            'type_id' => $this->type_id,
            'diameter'=> $this->diameter,
            'mass' => $this->mass,
            'rings' => $this->rings,
            'position' => $this->position
        );
    }


}