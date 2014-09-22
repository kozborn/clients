<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PiotrK\ClientBundle\Lib;

/**
 * Description of MyPaginator
 *
 * @author piotrek
 */
class Paginator {

  private $total;
  private $limit;
  private $nextPage;
  private $current;
  private $prevPage;
  private $pageCount;
  private $pages = array();

  public function __construct($current, $total, $limit) {
    $this->total = $total;
    $this->current = $current;
    $this->limit = $limit;
    $this->pageCount = ceil($this->total / $this->limit);
    $this->generate();
  }

  private function generate() {
    $this->getNextPage();
    $this->getPrevPage();
    for ($i = 1; $i <= $this->pageCount; $i++) {
      $this->pages[] = $i;
    }
  }

  public function getTotal() {
    return $this->total;
  }

  public function getNextPage() {
    $this->nextPage = $this->current < $this->pageCount ? $this->current + 1 : null;
    return $this->nextPage;
  }

  public function getPrevPage() {
    $this->prevPage = $this->current > 0 ? $this->current - 1 : null;
    return $this->prevPage;
  }

  public function getPages() {
    return $this->pages;
  }

  public function getCurrent() {
    return $this->current;
  }

}