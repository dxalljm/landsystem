<?php
namespace frontend\helpers;
class baseArraySearch
{
	private $data;
	private $where;

	public function __construct($data)
	{
		$this->data = $data;
		$this->where = $data->query->where;
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
// 		var_dump($operator);exit;
        switch (strtoupper($operator)) {
            case 'NOT':
            case 'AND':
            case 'OR':
                foreach ($condition as $i => $operand) {
//                 	var_dump($operand);
                    $subCondition = $this->whereToStr($operand);
//                     var_dump($subCondition);
                    if ($this->isEmpty($subCondition)) {
                        unset($condition[$i]);
                    } else {
                        $condition[$i] = $subCondition;
                    }
                }

                if (empty($condition)) {
                    return [];
                }
                break;
        }
        return $condition;
	}
	
	protected function isEmpty($value)
	{
		return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
	}
	
	public function search()
	{
		$this->where = $this->whereToStr($this->where);
		var_dump($this->where);exit;
		foreach ($this->where as $optionkey => $optionvalue) {
			$result = [];
			foreach ($this->data as $key => $value) {
				if($value->attributes[$optionkey] !== $optionvalue) {
					unset($this->data[$key]);
				}
			}
		}
		return $this->data;
	}
}