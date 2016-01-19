<?php
namespace frontend\helpers;
use app\models\Farms;
class baseArraySearch
{
	private $data;
	private $where;
	private $temp;
	private $namelist;
	public function __construct($data)
	{
		$this->data = $data->getModels();
		$this->temp = [];
		$this->where = $this->whereToStr($data->query->where);
		return $this;
	}
	public function whereToStr($condition)
	{
		if (!is_array($condition)) {
			return $condition;
		}
		
		if (!isset($condition[0])) {
			// hash format: 'column1' => 'value1', 'column2' => 'value2', ...
			foreach ($condition as $name => $value) {
				if ($this->isEmpty($value)) {
					unset($condition[$name]);
				}
			}
			return $condition;
		}
		$operator = array_shift($condition);
        switch (strtoupper($operator)) {
            case 'NOT':
            case 'AND':
            case 'OR':
                foreach ($condition as $i => $operand) {
                	$subcondition = $this->whereToStr($operand);
                    if ($this->isEmpty($subcondition)) {
                        unset($condition[$i]);
                    } else {
                        $condition[$i] = $subcondition;
                    }
                }
              	break;
            case 'LIKE':
            	return [$condition[0]=>$condition[1]];
            	break;
            default:
            	$condition = null;
        }
       	return $condition;
	}
	protected function isEmpty($value)
	{
		return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
	}
	protected function isOther($value)
	{
// 		var_dump($value);
// 		echo '-----------------';
// 		exit;
		if(is_array($value)) {
			if(array_key_exists(1, $value)) {
				if(strtoupper($value[0]) == 'BETWEEN' or strtoupper($value[0] == 'NOT BETWEEN'))
					return true;
				
			} else 
				return false;
		} else 
			return false;
	}
	
	public function search()
	{
// 		$this->whereToStr($this->where);
		var_dump($this->where);exit;
		if($this->where) {
			foreach ($this->where as $optionvalue) {
// 				var_dump($optionvalue);exit;
				foreach ($optionvalue as $okey=>$oval) {
// 					var_dump($oval);
					foreach ($this->data as $key => $value) {
// 						var_dump($oval);exit;
						if($value->attributes[$okey] == (int)$oval) {
							$this->temp[] = $this->data[$key];
						}
					}
				}
			}
		}
// 		var_dump($this->data);exit;
		return $this;
	}
	
	public function all()
	{
		if($this->temp)
			return $this->temp;
		else 
			return $this->data;
	}
	
	public function one()
	{
		return $this->data;
	}
	
	public function sum($field,$num = 1)
	{
		$sum = 0.0;
		if($this->temp)
			$data = $this->temp;
		else 
			$data = $this->data;
		foreach ($data as $key => $value) {
			if(is_numeric($value->attributes[$field])) {
				$sum += $value->attributes[$field];
			}
		}
		return  sprintf("%.2f", $sum/$num);;
	}
	public function count($field = NULL,$state = FALSE)
	{
// 		var_dump($field);
		$newdata = [];
		$olddata = [];
		if($this->temp)
			$data = $this->temp;
		else
			$data = $this->data;
// 		var_dump($data);exit;
		if(empty($field))
			return count($this->data);
		else {
			if($field == 'farmer_id' and $state) {
				$farm = [];
// 				var_dump($this->data);exit;
				foreach ($data as $key => $value) {
					$farm[] = Farms::find()->where(['id'=>$value->attributes['farms_id']])->one();
				}
				
				foreach ($farm as $k => $v) {
					$olddata[] = ['name'=>$v['farmername'],'cardid'=>$v['cardid']];
				}
// 				var_dump($data);exit;
				$newdata = $this->unique_arr($olddata);
				return count($newdata);
			} else {
				
				foreach ($data as $key => $value) {
					if(!empty($value->attributes[$field]) and $value->attributes[$field] !== 0 and $value->attributes[$field] !== '')
						$olddata[] = ['id'=>$value->attributes[$field]];
				}
// 				var_dump($olddata);exit;
				if($state) {
					if($olddata) {
						$newdata = $this->unique_arr($olddata);
						return count($newdata);
					} else 
						return 0;
				} else 
					return count($data);
			}
		}
	}
	
	public function getName($model,$getfield,$field)
	{
// 		$this->whereToStr($this->where);
// 		var_dump($this->where);
		$modelclass = 'app\\models\\'.$model;
		$data = [];
		$result = [];
		foreach ($this->data as $key => $value) {
			$data[$value->attributes['id']] = [$field=>$value->attributes[$field]];
		}
		$newdata = $this->unique_arr($data);
		foreach ($newdata as $key => $value) {
			if(!empty($value[$field]))
				$result[$value[$field]] = $modelclass::find()->where(['id'=>$value[$field]])->one()[$getfield];
		}
		$this->namelist = $result;
		return $this;
	}
	
	public function getOne($id)
	{
		return $this->namelist[$id];
	}
	public function getList()
	{
		return $this->namelist;
	}
	public function unique_arr($array2D, $stkeep = false, $ndformat = true) {
		// 判断是否保留一级数组键 (一级数组键可以为非数字)
		if ($stkeep)
			$stArr = array_keys ( $array2D );
			
// 		var_dump($array2D);exit;
		// 判断是否保留二级数组键 (所有二级数组键必须相同)
		if ($ndformat)
			$ndArr = array_keys(end($array2D));
			
		// 降维,也可以用implode,将一维数组转换为用逗号连接的字符串
		foreach ( $array2D as $v ) {
			$v = join ( ",", $v );
			$temp [] = $v;
		}
	
		// 去掉重复的字符串,也就是重复的一维数组
		$temp = array_unique ( $temp );
	
		// 再将拆开的数组重新组装
		foreach ( $temp as $k => $v ) {
			if ($stkeep)
				$k = $stArr [$k];
			if ($ndformat) {
				$tempArr = explode ( ",", $v );
				foreach ( $tempArr as $ndkey => $ndval )
					$output [$k] [$ndArr [$ndkey]] = $ndval;
			} else
				$output [$k] = explode ( ",", $v );
		}
	
		return $output;
	}
}