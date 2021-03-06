<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StatsHelper extends APP_Model
{
	public function prepare(&$data)
	{
		foreach($data as &$value)
		{
			if(is_numeric($value['ts']) === false)
				$value['ts'] = strtotime($value['ts']);
			$value['date'] = date(DATE_FORMAT_SHORT, $value['ts']);
		}
	}

	public function prepareChart($data)
	{
		$result = [
			'labels' => [],
			'values' => []
		];

		foreach($data as $value)
		{
			$result['labels'][] = $value['date'];
			$result['values'][] = $value['value'];
		}

		if(count($result['labels']))
			$result['labels'] = '"' . implode('","', $result['labels']) . '"';
		else
			$result['labels'] = '';

		if(count($result['values']))
			$result['values'] = implode(',', $result['values']);
		else
			$result['values'] = '';

		return $result;
	}

	public function prepareChartCoursesPie($data)
	{
		$result = [
			'labels' => [],
			'values' => []
		];

		foreach($data as $value)
		{
			$result['labels'][] = $value['name'];
			$result['values'][] = $value['value'];
		}

		if(count($result['labels']))
			$result['labels'] = '"' . implode('","', $result['labels']) . '"';
		else
			$result['labels'] = '';

		if(count($result['values']))
			$result['values'] = implode(',', $result['values']);
		else
			$result['values'] = '';

		return $result;
	}
	
	public function prepareChartCoursesMonth($data)
	{
		$result = [
			'labels' => [],
			'data' => []
		];

		foreach($data as $value)
		{
			if(empty($result['labels']))
				$result['labels'] = array_keys($value['values']);

			$result['data'][] = [
				'label' => $value['name'],
				'values' => array_values($value['values'])
			];
		}

		if(count($result['labels']))
			$result['labels'] = '"' . implode('","', $result['labels']) . '"';
		else
			$result['labels'] = '';

		return $result;
	}
	
}