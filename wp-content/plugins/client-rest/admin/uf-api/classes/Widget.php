<?php
class Widget {

  public $wid;
  public $stitle;
  public $fSid;
  public $wtitle;
  public $num_cards;
  public $link_text;
  public $purl;
  public $sharer;
  public $textnotes;
  public $widget_data;

  public function __construct($wid = '', $stitle = '', $fSid = '', $wtitle = '', $num_cards = '', $link_text = '', $purl = '', $sharer = '', $textnotes = '', $widget_data = '' ) {
    $this->wid = $wid;
    $this->stitle = $stitle;
    $this->fSid = $fSid;
    $this->wtitle = $wtitle;
    $this->num_cards = $num_cards;
    $this->link_text = $link_text;
    $this->purl = $purl;
    $this->sharer = $sharer;
    $this->textnotes = $textnotes;
    $this->widget_data = $widget_data;
  }

}
