<?php

namespace Instudies\SiteBundle\Entity;

interface BaseDeletableEntityInterface
{

	public function prePersistDeletableCallback();
	public function preUpdateDeletableCallback();

	public function getDeleted();
	public function setDeleted($deleted);

}