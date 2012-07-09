<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository,
	Instudies\SiteBundle\Helper\PagedQuery
;

/**
 * MessageRepository
 */
class MessageRepository extends EntityRepository
{

	public function findByUser ($userId, $filter = null, $page = 1, $perPage = 10)
	{

		$start = ($page-1)*$perPage;
		$next = ($page)*$perPage;

		if ($filter == null) {
			$condition = "";
		} else {
			$condition = "";
		}

		$query = $this->getEntityManager()
					->createQuery("
						SELECT
							message,
							reciever,
							sender,
							attachments,
							count(DISTINCT myFavourite.id) as myFavouriteCount
						FROM InstudiesSiteBundle:Message message
						LEFT JOIN message.reciever reciever
						LEFT JOIN message.sender sender
						LEFT JOIN message.favourites myFavourite WITH myFavourite.owner = :userId
						LEFT JOIN message.attachments attachments
						WHERE
							(message.sender = :userId AND ( message.deletedBySender = false OR message.deletedBySender IS NULL ) )
							OR
							(message.reciever = :userId AND ( message.deletedByReciever = false OR message.deletedByReciever IS NULL ) )
						".$condition."
						GROUP BY message.id, attachments.id
						ORDER BY message.created DESC
					");

		if ($page != 0) {

			$query
				->setFirstResult($start)
				->setMaxResults($perPage);

		}

		$result = $query
			->setParameter('userId', $userId)
			->getResult();

		$newResult = array();

		foreach ($result as $r) {
			if ($r['myFavouriteCount'] > 0) {
				$r[0]->inMyFavorites = true;
			} else {
				$r[0]->inMyFavorites = false;
			}
			if ($r[0]->getReciever()->getId() == $userId && !$r[0]->getReaded()) {
				$r[0]->isReaded = false;
			} else {
				$r[0]->isReaded = true;
			}
			$newResult[] = $r[0];
		}

		$nextPageItems = $this->getEntityManager()
			->createQueryBuilder()
			->select("message.id")
			->from("InstudiesSiteBundle:Message", "message")
			->where("(message.sender = :userId AND ( message.deletedBySender = false OR message.deletedBySender IS NULL ) ) OR (message.reciever = :userId AND ( message.deletedByReciever = false OR message.deletedByReciever IS NULL ) )" . $condition)
			->setParameter("userId", $userId)
			->getQuery()
			->setFirstResult($next)
			->setMaxResults($perPage)
			->getResult();

		return array(
				'messages' => $newResult,
				'next' => (count($nextPageItems) > 0) ? true : false
			);


	}

	public function findByUserAndIterlocutor ($userId, $interlocutorId, $page = 1, $perPage = 10)
	{

		$idsQuery =
			"
				SELECT
					DISTINCT message.id
				FROM InstudiesSiteBundle:Message message
				WHERE
					(
						(message.sender = :userId AND message.reciever = :interlocutorId)
							AND
						(message.deletedBySender = false OR message.deletedBySender IS NULL)
					)
					OR
					(
						(message.reciever = :userId AND message.sender = :interlocutorId)
							AND
						(message.deletedByReciever = false OR message.deletedByReciever IS NULL)
					)
				GROUP BY message.id
				ORDER BY message.created DESC
			";

		$idsQueryParameters =
			array(
					'userId' => $userId,
					'interlocutorId' => $interlocutorId
				);

		$itemsQuery =
			"
				SELECT
					message,
					reciever,
					sender,
					attachments,
					count(DISTINCT myFavourite.id) as myFavouriteCount
				FROM InstudiesSiteBundle:Message message
				LEFT JOIN message.reciever reciever
				LEFT JOIN message.sender sender
				LEFT JOIN message.favourites myFavourite WITH myFavourite.owner = :userId
				LEFT JOIN message.attachments attachments
				WHERE %ids_condition%
				GROUP BY message.id, attachments.id
				ORDER BY message.created ASC
			";

		$itemsQueryParameters =
			array(
					'userId' => $userId
				);

		$pagedQuery = new PagedQuery(
				$this->getEntityManager(),
				$page,
				$perPage,
				'message',
				$idsQuery,
				$idsQueryParameters,
				$itemsQuery,
				$itemsQueryParameters
			);

		$result = $pagedQuery->getResult();

		$newResult = array();

		if ($result) {

			foreach ($result as $r) {
				if ($r['myFavouriteCount'] > 0) {
					$r[0]->inMyFavorites = true;
				} else {
					$r[0]->inMyFavorites = false;
				}
				if ($r[0]->getReciever()->getId() == $userId && !$r[0]->getReaded()) {
					$r[0]->isReaded = false;
				} else {
					$r[0]->isReaded = true;
				}
				$newResult[] = $r[0];
			}

		}

		return array(
				'messages' => $newResult,
				'next' => $pagedQuery->getNext(),
				'itemsLeft' => $pagedQuery->getLeftItems()
			);


	}

	public function findByUserAndIterlocutorAndLastMessageId ($userId, $interlocutorId, $lastMessageId)
	{

		$result = $this->getEntityManager()
			->createQuery("
				SELECT
					message,
					reciever,
					sender,
					attachments,
					count(DISTINCT myFavourite.id) as myFavouriteCount
				FROM InstudiesSiteBundle:Message message
				LEFT JOIN message.reciever reciever
				LEFT JOIN message.sender sender
				LEFT JOIN message.favourites myFavourite WITH myFavourite.owner = :userId
				LEFT JOIN message.attachments attachments
				WHERE
					(
						(
							(message.sender = :userId AND message.reciever = :interlocutorId)
								AND
							(message.deletedBySender = false OR message.deletedBySender IS NULL)
						)
						OR
						(
							(message.reciever = :userId AND message.sender = :interlocutorId)
								AND
							(message.deletedByReciever = false OR message.deletedByReciever IS NULL)
						)
					)
					AND
						message.id > :lastMessageId
				GROUP BY message.id, attachments.id
				ORDER BY message.created DESC
			")
			->setParameter('userId', $userId)
			->setParameter('interlocutorId', $interlocutorId)
			->setParameter('lastMessageId', $lastMessageId)
			->getResult();

		$newResult = array();

		foreach (array_reverse($result) as $r) {
			if ($r['myFavouriteCount'] > 0) {
				$r[0]->inMyFavorites = true;
			} else {
				$r[0]->inMyFavorites = false;
			}
			if ($r[0]->getReciever()->getId() == $userId && !$r[0]->getReaded()) {
				$r[0]->isReaded = false;
			} else {
				$r[0]->isReaded = true;
			}
			$newResult[] = $r[0];
		}

		return $newResult;

	}

	public function removeByUserId ($message, $userId)
	{

        if ($message->getReciever()->getId() != $userId && $message->getSender()->getId() != $userId) {
            /* redirect */
	        return false;
        }

        if ($message->getReciever()->getId() == $userId) {
            $message->setDeletedByReciever(true);
            $message->setReaded(true);
        } elseif ($message->getSender()->getId() == $userId) {
            $message->setDeletedBySender(true);
        }

        $this->getEntityManager()->persist($message);
        $this->getEntityManager()->flush();

        return true;

	}

	public function findByEducationGroup ($userId, $educationGroupId, $page = 1, $perPage = 10)
	{

		$idsQuery =
			"
				SELECT
					DISTINCT message.id
				FROM InstudiesSiteBundle:Message message
				WHERE message.educationGroup = :educationGroupId
				GROUP BY message.id
				ORDER BY message.created DESC
			";

		$idsQueryParameters =
			array(
					'educationGroupId' => $educationGroupId
				);

		$itemsQuery =
			"
				SELECT
					message,
					sender
				FROM InstudiesSiteBundle:Message message
				LEFT JOIN message.sender sender
				WHERE %ids_condition%
				GROUP BY message.id
				ORDER BY message.created ASC
			";

		$itemsQueryParameters =
			array(
				);

		$pagedQuery = new PagedQuery(
				$this->getEntityManager(),
				$page,
				$perPage,
				'message',
				$idsQuery,
				$idsQueryParameters,
				$itemsQuery,
				$itemsQueryParameters
			);

		$result = $pagedQuery->getResult();

		return array(
				'messages' => $result,
				'next' => $pagedQuery->getNext(),
				'itemsLeft' => $pagedQuery->getLeftItems()
			);


	}

	public function findByEducationGroupAndLastMessageId ($userId, $educationGroupId, $lastMessageId)
	{

		$result = $this->getEntityManager()
			->createQuery("
				SELECT
					message,
					sender
				FROM InstudiesSiteBundle:Message message
				LEFT JOIN message.sender sender
				WHERE
					message.educationGroup = :educationGroupId
					AND
					message.id > :lastMessageId
				GROUP BY message.id
				ORDER BY message.created ASC
			")
			->setParameter('educationGroupId', $educationGroupId)
			->setParameter('lastMessageId', $lastMessageId)
			->getResult();

		return $result;

	}

	public function findUnreadedForNotification ()
	{

		$date = new \DateTime();
		$date->sub(new \DateInterval('PT180S'));

		return $this->getEntityManager()
			->createQuery("
				SELECT
					message
				FROM InstudiesSiteBundle:Message message
				JOIN message.reciever reciever WITH
					reciever.notificationInbox = true AND reciever.email != '' AND reciever.email IS NOT NULL
				WHERE 
					(message.readed = false OR message.readed IS NULL)
					AND
					(message.reciever IS NOT NULL)
					AND
					message.created < :date
					AND
					(message.recieverNotified = false OR message.recieverNotified IS NULL)
				ORDER BY message.created ASC
			")
			->setParameter('date', $date)
			->getResult();

	}

	public function read ($user, $item)
	{

		if (count($item) > 0 && count($user) > 0) {

			$item->setReaded(true);
			$this->getEntityManager()->persist($item);
			$this->getEntityManager()->flush();

		}

	}

}