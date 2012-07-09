<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository,
	Instudies\SiteBundle\Helper\PagedQuery
;

/**
 * ConversationRepository
 */
class ConversationRepository extends EntityRepository
{

	public function findByUser ($userId, $page = 1, $perPage = 10)
	{

		$idsQuery =
			"
				SELECT
					DISTINCT conversation.id
				FROM InstudiesSiteBundle:Conversation conversation
				WHERE
					(conversation.user1 = :userId)
					OR
					(conversation.user2 = :userId)
				GROUP BY conversation.id
				ORDER by conversation.updated DESC
			";

		$idsQueryParameters =
			array(
					'userId' => $userId
				);

		$itemsQuery = 
			"
				SELECT
					conversation,
					message,
					user1,
					user2,
					messageSender,
					messageReciever,
					attachments
				FROM InstudiesSiteBundle:Conversation conversation
				LEFT JOIN conversation.messages message
					WITH
						(message.sender = :userId AND (message.deletedBySender = false OR message.deletedBySender IS NULL))
						OR 
						(message.reciever = :userId AND (message.deletedByReciever = false OR message.deletedByReciever IS NULL))
				LEFT JOIN conversation.user1 user1
				LEFT JOIN conversation.user2 user2
				LEFT JOIN message.sender messageSender
				LEFT JOIN message.reciever messageReciever
				LEFT JOIN message.attachments attachments
				WHERE %ids_condition%
				GROUP BY conversation.id, message.id, attachments.id
				ORDER by message.created DESC
			";

		$itemsQueryParameters =
			array(
					'userId' => $userId
				);

		$pagedQuery = new PagedQuery(
				$this->getEntityManager(),
				$page,
				$perPage,
				'conversation',
				$idsQuery,
				$idsQueryParameters,
				$itemsQuery,
				$itemsQueryParameters
			);

		$result = $pagedQuery->getResult();

		$newResult = array();

		if ($result) {
			foreach ($result as $r) {
				$messages = $r->getMessages();
				$r->lastMessage = $messages[0];
				if (count($r->lastMessage) == 0) {
					continue;
				}
				if ($r->lastMessage->getReciever()->getId() == $userId && !$r->lastMessage->getReaded()) {
					$r->lastMessage->isReaded = false;
				} else {
					$r->lastMessage->isReaded = true;
				}
				$newResult[] = $r;
			}
		}

		return array(
				'conversations' => $newResult,
				'next' => $pagedQuery->getNext()
			);

	}

	public function findByUsers ($user1Id, $user2Id)
	{

		$result = $this->getEntityManager()
					->createQuery("
						SELECT
							conversation
						FROM InstudiesSiteBundle:Conversation conversation
						WHERE
							(conversation.user1 = :user1Id AND conversation.user2 = :user2Id)
							OR
							(conversation.user1 = :user2Id AND conversation.user2 = :user1Id)

					")
					->setParameter('user1Id', $user1Id)
					->setParameter('user2Id', $user2Id)
					->getOneOrNullResult();

		if (count($result) > 0) {

			return $result;

		} else {

			return false;

		}

	}

}