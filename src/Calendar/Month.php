<?php

declare(strict_types=1);

namespace YGGverse\Graph\Calendar;

class Month
{
  private $_time;
  private $_node = [];

  public function __construct(int $time = null, int $calendar = CAL_GREGORIAN)
  {
    // Set timestamp
    $this->_time = $time ? $time : time();

    // Generate calendar days
    for ($day = 1; $day <= cal_days_in_month($calendar, (int) date('n', $this->_time), (int) date('Y', $this->_time)); $day++)
    {
      $this->_node[$day][0] = [];
    }
  }

  public function addNode(int $day, int $value, string $label = null, string $class = null, int $layer = 0)
  {
    $this->_node[$day][$layer][] = [
      'value'  => $value,
      'label'  => $label,
      'class'  => $class,
      'width'  => 0,
      'height' => 0,
      'offset' => 0,
    ];
  }

  public function getNodes() : object
  {
    // Calculate month totals
    $total = [];

    foreach ($this->_node as $i => $day)
    {
      foreach ($day as $l => $layer)
      {
        $total[$i][$l] = 0;

        foreach ($layer as $data)
        {
          $total[$i][$l] += $data['value'];
        }
      }
    }

    // Calculate dimensions
    foreach ($this->_node as $i => $day)
    {
      foreach ($day as $l => $layer)
      {
        // Count data values in layer
        $count = 0;
        foreach ($layer as $data) $count++;

        // Calculate column width
        $width = $count ? 100 / $count : 0;

        // Calculate column width, height, offset
        foreach ($layer as $j => $data)
        {
          $this->_node[$i][$l][$j]['width']  = $width;
          $this->_node[$i][$l][$j]['height'] = $total[$i][$l] ? ceil($data['value'] / $total[$i][$l] * 100) : 0;
          $this->_node[$i][$l][$j]['offset'] = $width * $j;
        }
      }
    }

    // Return object
    return json_decode(json_encode($this->_node));
  }
}