<?php

class MArrayHelper {

	public static function toAssoc($data, $key = 'id', $value = NULL) {
		if (!$data)
			return array();
		$result = array();
		foreach ($data as $v) {
			if (is_object($v)) {
				$result[$v->$key] = ($value != NULL) ? $v->$value : $v;
			} else {
				$result[$v[$key]] = ($value != NULL) ? $v[$value] : $v;
			}
		}
		return $result;
	}

	public static function toArray($data, $field = 'id', $bDistinct = false) {
		$result = array();
		if (!$data)
			return $result;
		foreach ($data as $v) {
			$mCurrentValue = is_object($v)? $v->$field: $v[$field];
			if ($mCurrentValue) {
                if (($bDistinct && !in_array($mCurrentValue, $result)) || (!$bDistinct)) {
                    $result[] = $mCurrentValue;
                }
            }			
		}
		return $result;
	}

	public static function objectsToArray($data, $field = 'id', $bDistinct = false) {
		$result = array();
		if (!$data)
			return $result;
		foreach ($data as $v) {
			if ($bDistinct && $v->$field && !in_array($v->$field, $result))
				$result[] = $v->$field;
		}
		return $result;
	}

}
?>