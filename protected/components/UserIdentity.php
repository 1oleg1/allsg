<?php

/**
 * Этот компонент используется для аутентификации пользователя.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Аутентификация пользователя.
	 * @return boolean результаты аутентификации (true - если она прошла успешно).
	 */
    private $_id;
    public function authenticate()
    {
    	//ищем пользователя по имени
        $record=Users::model()->findByAttributes(array('u_login'=>$this->username));
        //если пользователь найден и его пароль совпадает с введенным...
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($record->u_password!==md5($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
        	//...сохраняем данные пользователя (имя и адрес фида)
        	// (в принципе, адрес фида можно не сохранять, тогда при импорте игр
        	// нужно будет выполнить дополнительный запрос)
            $this->_id=$record->u_id;
            $this->setState('name', $record->u_name);
            $this->setState('xml', $record->u_xml);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}