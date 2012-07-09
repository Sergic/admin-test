<?php

namespace Instudies\SiteBundle\Entity;

interface BaseTimestampableEntityInterface
{

	public function prePersistTimestampableCallback();
	public function preUpdateTimestampableCallback();

	public function getCreated();
	public function setCreated($created);
	public function getUpdated();
	public function setUpdated($updated);

}