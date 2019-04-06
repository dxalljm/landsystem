<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 */
class PermissionForm extends Item
{
         
    public function init() {

        parent::init();
        //常量TYPE_ROLE=1
        $this->type = \yii\rbac\Item::TYPE_PERMISSION;

    }
    
    public static function allClass()
    {
    	$backendControllerDir = self::getFile('../controllers/','backend');
    	$frontendControllerDir = self::getFile('../../frontend/controllers/','frontend');
    	$allClassInfo = array_merge($frontendControllerDir,$backendControllerDir);
    	return $allClassInfo;
    }
    
    public static function getFile($dir,$path) {
    	$fileArray[]=NULL;
    	if (false != ($handle = opendir ( $dir ))) {
    		$i=0;
    		while ( false !== ($file = readdir ( $handle )) ) {
    			//去掉"“.”、“..”以及带“.xxx”后缀的文件
    			if ($file != "." && $file != ".."&&strpos($file,".")) {
    				$fileArray[$i]="./imageroot/current/".$file;
    				if($i==100){
    					break;
    				}
    				$i++;
    			}
    		}
    		//关闭句柄
    		closedir ( $handle );
    	}
    	$i=0;
    	foreach ($fileArray as $value) {
    		$filenameArray = explode('/', $value);
    		$strArray = explode('.',  $filenameArray[3]);
    		$result[$strArray[0]] = [
    				'classname' => $strArray[0],
    				'path' => $path.'\\controllers\\',
    		];
    		$i++;
    	}
    	return $result;
    }
}
