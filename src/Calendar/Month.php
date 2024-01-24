<?php

declare(strict_types=1);

namespace Yggverse\Graph\Calendar;

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

  public function getNodes(string $height = 'month') : object
  {
    // Calculate totals
    $hours = [];
    $month = 0;

    foreach ($this->_node as $i => $day)
    {
      foreach ($day as $l => $layer)
      {
        $hours[$i][$l] = 0;

        foreach ($layer as $data)
        {
          $hours[$i][$l] += $data['value'];
        }

        $month = $month + $hours[$i][$l];
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
          switch ($height)
          {
            case 'day':
              $this->_node[$i][$l][$j]['height'] = $hours[$i][$l] ? ceil($data['value'] / $hours[$i][$l] * 100) : 0;
            break;
            default:
              $this->_node[$i][$l][$j]['height'] = $month ? round($data['value'] * ($month / 100)) : 0;
          }

          $this->_node[$i][$l][$j]['width']  = $width;
          $this->_node[$i][$l][$j]['offset'] = $width * $j;
        }
      }
    }

    // Return object
    return json_decode(json_encode($this->_node));
  }
}