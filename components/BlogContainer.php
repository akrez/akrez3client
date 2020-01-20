<?php

namespace app\components;

use yii\base\Component;

class BlogContainer extends Component
{

    private $_identity = null;

    public function setIdentity($identity)
    {
        $this->_identity = $identity;
    }

    public function getIdentity()
    {
        return $this->_identity;
    }

    public function attribute($attribute)
    {
        if (is_array($this->_identity) && array_key_exists($attribute, $this->_identity)) {
            return $this->_identity[$attribute];
        }
        return null;
    }

}
