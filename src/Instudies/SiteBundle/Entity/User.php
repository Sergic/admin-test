<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert,
    Symfony\Component\Security\Core\User\UserInterface
;

/**
 * InstudiesSiteBundle\Entity\User
 *
 * @ORM\Table(name="User")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\UserRepository")
 * @DoctrineAssert\UniqueEntity(fields="email", message="User with such email has been allready registered")
 */
class User  extends BaseTimestampableDeletableEntity implements UserInterface
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var string $newEmail
     *
     * @ORM\Column(name="newEmail", type="string", length=255, nullable=true)
     */
    protected $newEmail;

    /**
     * @var string $registrationReferrer
     *
     * @ORM\Column(name="registrationReferrer", type="text", nullable=true)
     */
    protected $registrationReferrer;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string salt
     */
    protected $salt;

    protected $fullname;

    /**
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    protected $lastname;

    /**
     * @var string $gender
     *
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     */
    protected $gender;

    /**
     * @var string $timezone
     *
     * @ORM\Column(type="string", length=255, name="timezone", nullable=true)
     */
    protected $timezone;

    /**
     * @var boolean $emailActivated
     *
     * @ORM\Column(name="emailActivated", type="boolean")
     */
    protected $emailActivated;

    /**
     * @var boolean $admin
     *
     * @ORM\Column(name="admin", type="boolean", nullable=true)
     */
    protected $admin;

    /**
     * @var boolean $support
     *
     * @ORM\Column(name="support", type="boolean", nullable=true)
     */
    protected $support;

    /**
     * @var string $emailActivationCode
     *
     * @ORM\Column(name="emailActivationCode", type="string", length=255, nullable=true)
     */
    protected $emailActivationCode;

    /**
     * @var string $emailChangeCode
     *
     * @ORM\Column(name="emailChangeCode", type="string", length=255, nullable=true)
     */
    protected $emailChangeCode;

    /**
     * @var boolean $filledAllInformation
     *
     * @ORM\Column(name="filledAllInformation", type="boolean")
     */
    protected $filledAllInformation;

    /**
     * @var string $resetPasswordCode
     *
     * @ORM\Column(name="resetPasswordCode", type="string", length=255, nullable=true)
     */
    protected $resetPasswordCode;

    /**
     * @var string $lastUsedIp
     *
     * @ORM\Column(name="lastUsedIp", type="string", length=255, nullable=true)
     */
    protected $lastUsedIp;

    /**
     * @ORM\Column(type="datetime", name="lastVisit", nullable=true)
     *
     * @var DateTime $lastVisit
     */
    protected $lastVisit;

    /**
     * @ORM\Column(type="string", length=255, name="lastVisitIp", nullable=true)
     *
     * @var string $lastVisitIp
     */
    protected $lastVisitIp;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $lastVisitGroup;

    protected $online;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="UserRole",
     *     joinColumns={@ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role", referencedColumnName="id", onDelete="CASCADE")}
     * )
     *
     * @var ArrayCollection $userRoles
     */
    protected $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="UserEducationGroup", mappedBy="user")
     */
    protected $groups;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="user")
     */
    protected $activities;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="sender")
     */
    protected $sendedMessages;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="reciever")
     */
    protected $recievedMessages;

    /**
     * @ORM\OneToMany(targetEntity="Conversation", mappedBy="user1")
     */
    protected $conversations1;

    /**
     * @ORM\OneToMany(targetEntity="Conversation", mappedBy="user2")
     */
    protected $conversations2;

    /**
     * @ORM\OneToMany(targetEntity="Favourite", mappedBy="user")
     */
    protected $favourites;

    /**
     * @ORM\OneToMany(targetEntity="Favourite", mappedBy="owner")
     */
    protected $myFavourites;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="reciever")
     */
    protected $recievedNotifications;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="sender")
     */
    protected $sendedNotifications;

    /**
     * @ORM\OneToMany(targetEntity="Invitation", mappedBy="sender")
     */
    protected $invitations;

    /**
     * @ORM\OneToMany(targetEntity="JoinEducationGroupRequest", mappedBy="user")
     */
    protected $joinEducationGroupRequests;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupBlogPost", mappedBy="user")
     */
    protected $educationGroupBlogPosts;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupEventPost", mappedBy="user")
     */
    protected $educationGroupEventPosts;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupHomeworkPost", mappedBy="user")
     */
    protected $educationGroupHomeworkPosts;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupSummaryPost", mappedBy="user")
     */
    protected $educationGroupSummaryPosts;

    /**
     * @ORM\OneToMany(targetEntity="ReadedComment", mappedBy="user")
     */
    protected $readedComment;

    /**
     * @ORM\OneToMany(targetEntity="ReadedEducationGroupBlogPost", mappedBy="user")
     */
    protected $readedEducationGroupBlogPost;

    /**
     * @ORM\OneToMany(targetEntity="NotifiedEducationGroupBlogPost", mappedBy="user")
     */
    protected $notifiedEducationGroupBlogPost;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedEducationGroupBlogPost", mappedBy="user")
     */
    protected $unreadedEducationGroupBlogPost;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedEducationGroupHomeworkPost", mappedBy="user")
     */
    protected $unreadedEducationGroupHomeworkPost;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedEducationGroupSummaryPost", mappedBy="user")
     */
    protected $unreadedEducationGroupSummaryPost;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedEducationGroupEventPost", mappedBy="user")
     */
    protected $unreadedEducationGroupEventPost;

    /**
     * @ORM\OneToMany(targetEntity="ReadedActivity", mappedBy="user")
     */
    protected $readedActivity;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedActivity", mappedBy="user")
     */
    protected $unreadedActivity;

    /**
     * @ORM\OneToMany(targetEntity="Feedback", mappedBy="user")
     */
    protected $feedbacks;

    /**
     * @var string $homePhone
     *
     * @ORM\Column(type="string", length=255, name="homePhone", nullable=true)
     */
    protected $homePhone;

    /**
     * @var string $mobilePhone
     *
     * @ORM\Column(type="string", length=255, name="mobilePhone", nullable=true)
     */
    protected $mobilePhone;

    /**
     * @var string $skype
     *
     * @ORM\Column(type="string", length=255, name="skype", nullable=true)
     */
    protected $skype;

    /**
     * @var string $icq
     *
     * @ORM\Column(type="string", length=255, name="icq", nullable=true)
     */
    protected $icq;

    /**
     * @var string $site
     *
     * @ORM\Column(type="string", length=255, name="site", nullable=true)
     */
    protected $site;

    /**
     * @var boolean $facebook
     *
     * @ORM\Column(name="facebook", type="boolean", nullable=true)
     */
    protected $facebook;

    /**
     * @var string $facebookId
     *
     * @ORM\Column(type="string", length=255, name="facebookId", nullable=true)
     */
    protected $facebookId;

    /**
     * @var string $facebookTitle
     *
     * @ORM\Column(type="string", length=255, name="facebookTitle", nullable=true)
     */
    protected $facebookTitle;

    /**
     * @var string $facebookToken
     *
     * @ORM\Column(type="string", length=255, name="facebookToken", nullable=true)
     */
    protected $facebookToken;

    /**
     * @var boolean $twitter
     *
     * @ORM\Column(name="twitter", type="boolean", nullable=true)
     */
    protected $twitter;

    /**
     * @var string $twitterId
     *
     * @ORM\Column(type="string", length=255, name="twitterId", nullable=true)
     */
    protected $twitterId;

    /**
     * @var string $facebookTitle
     *
     * @ORM\Column(type="string", length=255, name="twitterTitle", nullable=true)
     */
    protected $twitterTitle;

    /**
     * @var string $twitterToken
     *
     * @ORM\Column(type="string", length=255, name="twitterToken", nullable=true)
     */
    protected $twitterToken;

    /**
     * @var boolean $vkontakte
     *
     * @ORM\Column(name="vkontakte", type="boolean", nullable=true)
     */
    protected $vkontakte;

    /**
     * @var string $vkontakteId
     *
     * @ORM\Column(type="string", length=255, name="vkontakteId", nullable=true)
     */
    protected $vkontakteId;

    /**
     * @var string $vkontakteTitle
     *
     * @ORM\Column(type="string", length=255, name="vkontakteTitle", nullable=true)
     */
    protected $vkontakteTitle;

    /**
     * @var string $vkontakteToken
     *
     * @ORM\Column(type="string", length=255, name="vkontakteToken", nullable=true)
     */
    protected $vkontakteToken;

    /**
     * @var boolean $mailru
     *
     * @ORM\Column(name="mailru", type="boolean", nullable=true)
     */
    protected $mailru;

    /**
     * @var string $mailruId
     *
     * @ORM\Column(type="string", length=255, name="mailruId", nullable=true)
     */
    protected $mailruId;

    /**
     * @var string $mailruTitle
     *
     * @ORM\Column(type="string", length=255, name="mailruTitle", nullable=true)
     */
    protected $mailruTitle;

    /**
     * @var string $mailruToken
     *
     * @ORM\Column(type="string", length=255, name="mailruToken", nullable=true)
     */
    protected $mailruToken;

    /**
     * @var string $mailruLink
     *
     * @ORM\Column(type="string", length=255, name="mailruLink", nullable=true)
     */
    protected $mailruLink;

    /**
     * @var string $notificationEmail
     *
     * @ORM\Column(type="boolean", name="notificationEmail", nullable=true)
     */
    protected $notificationEmail;

    /**
     * @var string $notificationHomework
     *
     * @ORM\Column(type="boolean", name="notificationHomework", nullable=true)
     */
    protected $notificationHomework;

    /**
     * @var string $notificationSummary
     *
     * @ORM\Column(type="boolean", name="notificationSummary", nullable=true)
     */
    protected $notificationSummary;

    /**
     * @var string $notificationEvent
     *
     * @ORM\Column(type="boolean", name="notificationEvent", nullable=true)
     */
    protected $notificationEvent;

    /**
     * @var string $notificationBlog
     *
     * @ORM\Column(type="boolean", name="notificationBlog", nullable=true)
     */
    protected $notificationBlog;

    /**
     * @var string $notificationCommentEntry
     *
     * @ORM\Column(type="boolean", name="notificationCommentEntry", nullable=true)
     */
    protected $notificationCommentEntry;

    /**
     * @var string $notificationCommentAnswer
     *
     * @ORM\Column(type="boolean", name="notificationCommentAnswer", nullable=true)
     */
    protected $notificationCommentAnswer;

    /**
     * @var string $notificationGroupIvitation
     *
     * @ORM\Column(type="boolean", name="notificationGroupIvitation", nullable=true)
     */
    protected $notificationGroupIvitation;

    /**
     * @var string $notificationInbox
     *
     * @ORM\Column(type="boolean", name="notificationInbox", nullable=true)
     */
    protected $notificationInbox;

    /**
     * @var string $notificationSite
     *
     * @ORM\Column(type="boolean", name="notificationSite", nullable=true)
     */
    protected $notificationSite;

    /**
     * @var string $avatar
     *
     * @ORM\Column(type="string", length=255, name="avatar", nullable=true)
     */
    protected $avatar;

    /**
     * @var boolean $notifiedUnactiveGroupCreatorFirstTime
     *
     * @ORM\Column(type="boolean", name="notifiedUnactiveGroupCreatorFirstTime", nullable=true)
     */
    protected $notifiedUnactiveGroupCreatorFirstTime;

    /**
     * @var boolean $notifiedUnactiveGroupCreatorSecondTime
     *
     * @ORM\Column(type="boolean", name="notifiedUnactiveGroupCreatorSecondTime", nullable=true)
     */
    protected $notifiedUnactiveGroupCreatorSecondTime;

    public $inMyFavorites;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return 
            ($this->firstname!=""||$this->lastname!="")?
                $this->firstname . ' ' .$this->lastname:
                ($this->email)?
                    $this->email:
                        $this->id;
    }

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->favourites = new ArrayCollection();
        $this->myFavourites = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->recievedNotifications = new ArrayCollection();
        $this->sendedNotifications = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->joinEducationGroupRequests = new ArrayCollection();
        $this->educationGroupBlogPosts = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
        $this->sendedMessages = new ArrayCollection();
        $this->recievedMessages = new ArrayCollection();
        $this->conversations1 = new ArrayCollection();
        $this->conversations2 = new ArrayCollection();
        $this->readed = new ArrayCollection();
        $this->unreaded = new ArrayCollection();
    }

    public function __sleep()
    {
        $properties = get_object_vars($this);
        unset($properties['groups']);
        unset($properties['activities']);
        unset($properties['favourites']);
        unset($properties['myFavourites']);
        unset($properties['comments']);
        unset($properties['recievedNotifications']);
        unset($properties['sendedNotifications']);
        unset($properties['invitations']);
        unset($properties['joinEducationGroupRequests']);
        unset($properties['educationGroupBlogPosts']);
        unset($properties['educationGroupEventPosts']);
        unset($properties['educationGroupHomeworkPosts']);
        unset($properties['educationGroupSummaryPosts']);
        unset($properties['feedbacks']);
        unset($properties['lastVisitGroup']);
        unset($properties['sendedMessages']);
        unset($properties['recievedMessages']);
        unset($properties['conversations1']);
        unset($properties['conversations2']);
        return array_keys($properties);
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public function serializeUserInfo ()
    {
        return $this->getFirstname() . $this->getLastname() . $this->getHomePhone() . $this->getMobilePhone() . $this->getSkype() . $this->getIcq() . $this->getSite();
    }

    public function timezoneObj ()
    {

        if ($this->timezone != "" && in_array($this->timezone, \DateTimeZone::listIdentifiers())) {
            return new \DateTimeZone($this->timezone);
        }

        $d = new \DateTime();

        return $d->getTimezone();

    }

    /**********************************************************************************
     * USER INTERFACE IMPLEMENT
     *********************************************************************************/

     /**
      * Gets fullname
      *
      * @return string The fullname
      */
     public function getFullname()
     {
        return 
            ($this->firstname!=""||$this->lastname!="")?
                ($this->firstname . ' ' .$this->lastname) :
                (
                    ($this->email)?
                        $this->email:
                        $this->id
                );
     }

    /**
     * Gets the username.
     *
     * @return string The username.
     */
    public function getUsername()
    {
        return 
            ($this->firstname!=""||$this->lastname!="")?
                $this->firstname . ' ' .$this->lastname:
                ($this->email)?
                    $this->email:
                        $this->id;
    }

    /**
     * Erases the user credentials.
     */
    public function eraseCredentials()
    {
    }

    /**
     * Gets an array of roles.
     * 
     * @return array An array of Role objects
     */
    public function getRoles()
    {
        return $this->getUserRoles()->toArray();
    }

    /**
     * Compares this user to another to determine if they are the same.
     * 
     * @param UserInterface $user The user
     * @return boolean True if equal, false othwerwise.
     */
    public function equals(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    public function setDefaultNotificationsSettings()
    {

        $this->notificationEmail = true;
        $this->notificationHomework = true;
        $this->notificationSummary = true;
        $this->notificationEvent = true;
        $this->notificationBlog = true;
        $this->notificationCommentEntry = true;
        $this->notificationCommentAnswer = true;
        $this->notificationGroupIvitation = true;
        $this->notificationInbox = true;
        $this->notificationSite = true;

    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set newEmail
     *
     * @param string $newEmail
     */
    public function setNewEmail($newEmail)
    {
        $this->newEmail = $newEmail;
    }

    /**
     * Get newEmail
     *
     * @return string 
     */
    public function getNewEmail()
    {
        return $this->newEmail;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set gender
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * Get timezone
     *
     * @return string 
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set emailActivated
     *
     * @param boolean $emailActivated
     */
    public function setEmailActivated($emailActivated)
    {
        $this->emailActivated = $emailActivated;
    }

    /**
     * Get emailActivated
     *
     * @return boolean 
     */
    public function getEmailActivated()
    {
        return $this->emailActivated;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    /**
     * Get admin
     *
     * @return boolean 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set support
     *
     * @param boolean $support
     */
    public function setSupport($support)
    {
        $this->support = $support;
    }

    /**
     * Get support
     *
     * @return boolean 
     */
    public function getSupport()
    {
        return $this->support;
    }

    /**
     * Set emailActivationCode
     *
     * @param string $emailActivationCode
     */
    public function setEmailActivationCode($emailActivationCode)
    {
        $this->emailActivationCode = $emailActivationCode;
    }

    /**
     * Get emailActivationCode
     *
     * @return string 
     */
    public function getEmailActivationCode()
    {
        return $this->emailActivationCode;
    }

    /**
     * Set emailChangeCode
     *
     * @param string $emailChangeCode
     */
    public function setEmailChangeCode($emailChangeCode)
    {
        $this->emailChangeCode = $emailChangeCode;
    }

    /**
     * Get emailChangeCode
     *
     * @return string 
     */
    public function getEmailChangeCode()
    {
        return $this->emailChangeCode;
    }

    /**
     * Set filledAllInformation
     *
     * @param boolean $filledAllInformation
     */
    public function setFilledAllInformation($filledAllInformation)
    {
        $this->filledAllInformation = $filledAllInformation;
    }

    /**
     * Get filledAllInformation
     *
     * @return boolean 
     */
    public function getFilledAllInformation()
    {
        return $this->filledAllInformation;
    }

    /**
     * Set resetPasswordCode
     *
     * @param string $resetPasswordCode
     */
    public function setResetPasswordCode($resetPasswordCode)
    {
        $this->resetPasswordCode = $resetPasswordCode;
    }

    /**
     * Get resetPasswordCode
     *
     * @return string 
     */
    public function getResetPasswordCode()
    {
        return $this->resetPasswordCode;
    }

    /**
     * Set lastUsedIp
     *
     * @param string $lastUsedIp
     */
    public function setLastUsedIp($lastUsedIp)
    {
        $this->lastUsedIp = $lastUsedIp;
    }

    /**
     * Get lastUsedIp
     *
     * @return string 
     */
    public function getLastUsedIp()
    {
        return $this->lastUsedIp;
    }

    /**
     * Set lastVisit
     *
     * @param datetime $lastVisit
     */
    public function setLastVisit($lastVisit)
    {
        $this->lastVisit = $lastVisit;
    }

    /**
     * Get lastVisit
     *
     * @return datetime 
     */
    public function getLastVisit()
    {
        return $this->lastVisit;
    }

    /**
     * Set lastVisitIp
     *
     * @param string $lastVisitIp
     */
    public function setLastVisitIp($lastVisitIp)
    {
        $this->lastVisitIp = $lastVisitIp;
    }

    /**
     * Get lastVisitIp
     *
     * @return string 
     */
    public function getLastVisitIp()
    {
        return $this->lastVisitIp;
    }

    /**
     * Set homePhone
     *
     * @param string $homePhone
     */
    public function setHomePhone($homePhone)
    {
        $this->homePhone = $homePhone;
    }

    /**
     * Get homePhone
     *
     * @return string 
     */
    public function getHomePhone()
    {
        return $this->homePhone;
    }

    /**
     * Set mobilePhone
     *
     * @param string $mobilePhone
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * Get mobilePhone
     *
     * @return string 
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * Set skype
     *
     * @param string $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * Get skype
     *
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set icq
     *
     * @param string $icq
     */
    public function setIcq($icq)
    {
        $this->icq = $icq;
    }

    /**
     * Get icq
     *
     * @return string 
     */
    public function getIcq()
    {
        return $this->icq;
    }

    /**
     * Set site
     *
     * @param string $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * Get site
     *
     * @return string 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set facebook
     *
     * @param boolean $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * Get facebook
     *
     * @return boolean 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set facebookTitle
     *
     * @param string $facebookTitle
     */
    public function setFacebookTitle($facebookTitle)
    {
        $this->facebookTitle = $facebookTitle;
    }

    /**
     * Get facebookTitle
     *
     * @return string 
     */
    public function getFacebookTitle()
    {
        return $this->facebookTitle;
    }

    /**
     * Set facebookToken
     *
     * @param string $facebookToken
     */
    public function setFacebookToken($facebookToken)
    {
        $this->facebookToken = $facebookToken;
    }

    /**
     * Get facebookToken
     *
     * @return string 
     */
    public function getFacebookToken()
    {
        return $this->facebookToken;
    }

    /**
     * Set twitter
     *
     * @param boolean $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * Get twitter
     *
     * @return boolean 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set twitterId
     *
     * @param string $twitterId
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;
    }

    /**
     * Get twitterId
     *
     * @return string 
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }

    /**
     * Set twitterTitle
     *
     * @param string $twitterTitle
     */
    public function setTwitterTitle($twitterTitle)
    {
        $this->twitterTitle = $twitterTitle;
    }

    /**
     * Get twitterTitle
     *
     * @return string 
     */
    public function getTwitterTitle()
    {
        return $this->twitterTitle;
    }

    /**
     * Set twitterToken
     *
     * @param string $twitterToken
     */
    public function setTwitterToken($twitterToken)
    {
        $this->twitterToken = $twitterToken;
    }

    /**
     * Get twitterToken
     *
     * @return string 
     */
    public function getTwitterToken()
    {
        return $this->twitterToken;
    }

    /**
     * Set vkontakte
     *
     * @param boolean $vkontakte
     */
    public function setVkontakte($vkontakte)
    {
        $this->vkontakte = $vkontakte;
    }

    /**
     * Get vkontakte
     *
     * @return boolean 
     */
    public function getVkontakte()
    {
        return $this->vkontakte;
    }

    /**
     * Set vkontakteId
     *
     * @param string $vkontakteId
     */
    public function setVkontakteId($vkontakteId)
    {
        $this->vkontakteId = $vkontakteId;
    }

    /**
     * Get vkontakteId
     *
     * @return string 
     */
    public function getVkontakteId()
    {
        return $this->vkontakteId;
    }

    /**
     * Set vkontakteTitle
     *
     * @param string $vkontakteTitle
     */
    public function setVkontakteTitle($vkontakteTitle)
    {
        $this->vkontakteTitle = $vkontakteTitle;
    }

    /**
     * Get vkontakteTitle
     *
     * @return string 
     */
    public function getVkontakteTitle()
    {
        return $this->vkontakteTitle;
    }

    /**
     * Set vkontakteToken
     *
     * @param string $vkontakteToken
     */
    public function setVkontakteToken($vkontakteToken)
    {
        $this->vkontakteToken = $vkontakteToken;
    }

    /**
     * Get vkontakteToken
     *
     * @return string 
     */
    public function getVkontakteToken()
    {
        return $this->vkontakteToken;
    }

    /**
     * Set mailru
     *
     * @param boolean $mailru
     */
    public function setMailru($mailru)
    {
        $this->mailru = $mailru;
    }

    /**
     * Get mailru
     *
     * @return boolean 
     */
    public function getMailru()
    {
        return $this->mailru;
    }

    /**
     * Set mailruId
     *
     * @param string $mailruId
     */
    public function setMailruId($mailruId)
    {
        $this->mailruId = $mailruId;
    }

    /**
     * Get mailruId
     *
     * @return string 
     */
    public function getMailruId()
    {
        return $this->mailruId;
    }

    /**
     * Set mailruTitle
     *
     * @param string $mailruTitle
     */
    public function setMailruTitle($mailruTitle)
    {
        $this->mailruTitle = $mailruTitle;
    }

    /**
     * Get mailruTitle
     *
     * @return string 
     */
    public function getMailruTitle()
    {
        return $this->mailruTitle;
    }

    /**
     * Set mailruToken
     *
     * @param string $mailruToken
     */
    public function setMailruToken($mailruToken)
    {
        $this->mailruToken = $mailruToken;
    }

    /**
     * Get mailruToken
     *
     * @return string 
     */
    public function getMailruToken()
    {
        return $this->mailruToken;
    }

    /**
     * Set mailruLink
     *
     * @param string $mailruLink
     */
    public function setMailruLink($mailruLink)
    {
        $this->mailruLink = $mailruLink;
    }

    /**
     * Get mailruLink
     *
     * @return string 
     */
    public function getMailruLink()
    {
        return $this->mailruLink;
    }

    /**
     * Set notificationEmail
     *
     * @param boolean $notificationEmail
     */
    public function setNotificationEmail($notificationEmail)
    {
        $this->notificationEmail = $notificationEmail;
    }

    /**
     * Get notificationEmail
     *
     * @return boolean 
     */
    public function getNotificationEmail()
    {
        return $this->notificationEmail;
    }

    /**
     * Set notificationHomework
     *
     * @param boolean $notificationHomework
     */
    public function setNotificationHomework($notificationHomework)
    {
        $this->notificationHomework = $notificationHomework;
    }

    /**
     * Get notificationHomework
     *
     * @return boolean 
     */
    public function getNotificationHomework()
    {
        return $this->notificationHomework;
    }

    /**
     * Set notificationSummary
     *
     * @param boolean $notificationSummary
     */
    public function setNotificationSummary($notificationSummary)
    {
        $this->notificationSummary = $notificationSummary;
    }

    /**
     * Get notificationSummary
     *
     * @return boolean 
     */
    public function getNotificationSummary()
    {
        return $this->notificationSummary;
    }

    /**
     * Set notificationEvent
     *
     * @param boolean $notificationEvent
     */
    public function setNotificationEvent($notificationEvent)
    {
        $this->notificationEvent = $notificationEvent;
    }

    /**
     * Get notificationEvent
     *
     * @return boolean 
     */
    public function getNotificationEvent()
    {
        return $this->notificationEvent;
    }

    /**
     * Set notificationBlog
     *
     * @param boolean $notificationBlog
     */
    public function setNotificationBlog($notificationBlog)
    {
        $this->notificationBlog = $notificationBlog;
    }

    /**
     * Get notificationBlog
     *
     * @return boolean 
     */
    public function getNotificationBlog()
    {
        return $this->notificationBlog;
    }

    /**
     * Set notificationCommentEntry
     *
     * @param boolean $notificationCommentEntry
     */
    public function setNotificationCommentEntry($notificationCommentEntry)
    {
        $this->notificationCommentEntry = $notificationCommentEntry;
    }

    /**
     * Get notificationCommentEntry
     *
     * @return boolean 
     */
    public function getNotificationCommentEntry()
    {
        return $this->notificationCommentEntry;
    }

    /**
     * Set notificationCommentAnswer
     *
     * @param boolean $notificationCommentAnswer
     */
    public function setNotificationCommentAnswer($notificationCommentAnswer)
    {
        $this->notificationCommentAnswer = $notificationCommentAnswer;
    }

    /**
     * Get notificationCommentAnswer
     *
     * @return boolean 
     */
    public function getNotificationCommentAnswer()
    {
        return $this->notificationCommentAnswer;
    }

    /**
     * Set notificationGroupIvitation
     *
     * @param boolean $notificationGroupIvitation
     */
    public function setNotificationGroupIvitation($notificationGroupIvitation)
    {
        $this->notificationGroupIvitation = $notificationGroupIvitation;
    }

    /**
     * Get notificationGroupIvitation
     *
     * @return boolean 
     */
    public function getNotificationGroupIvitation()
    {
        return $this->notificationGroupIvitation;
    }

    /**
     * Set notificationInbox
     *
     * @param boolean $notificationInbox
     */
    public function setNotificationInbox($notificationInbox)
    {
        $this->notificationInbox = $notificationInbox;
    }

    /**
     * Get notificationInbox
     *
     * @return boolean 
     */
    public function getNotificationInbox()
    {
        return $this->notificationInbox;
    }

    /**
     * Set notificationSite
     *
     * @param boolean $notificationSite
     */
    public function setNotificationSite($notificationSite)
    {
        $this->notificationSite = $notificationSite;
    }

    /**
     * Get notificationSite
     *
     * @return boolean 
     */
    public function getNotificationSite()
    {
        return $this->notificationSite;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set lastVisitGroup
     *
     * @param Instudies\SiteBundle\Entity\EducationGroup $lastVisitGroup
     */
    public function setLastVisitGroup(\Instudies\SiteBundle\Entity\EducationGroup $lastVisitGroup)
    {
        $this->lastVisitGroup = $lastVisitGroup;
    }

    /**
     * Get lastVisitGroup
     *
     * @return Instudies\SiteBundle\Entity\EducationGroup 
     */
    public function getLastVisitGroup()
    {
        return $this->lastVisitGroup;
    }

    /**
     * Add userRoles
     *
     * @param Instudies\SiteBundle\Entity\Role $userRoles
     */
    public function addRole(\Instudies\SiteBundle\Entity\Role $userRoles)
    {
        $this->userRoles[] = $userRoles;
    }

    /**
     * Get userRoles
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Add groups
     *
     * @param Instudies\SiteBundle\Entity\UserEducationGroup $groups
     */
    public function addUserEducationGroup(\Instudies\SiteBundle\Entity\UserEducationGroup $groups)
    {
        $this->groups[] = $groups;
    }

    /**
     * Get groups
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add activities
     *
     * @param Instudies\SiteBundle\Entity\Activity $activities
     */
    public function addActivity(\Instudies\SiteBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;
    }

    /**
     * Get activities
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * Add sendedMessages
     *
     * @param Instudies\SiteBundle\Entity\Message $sendedMessages
     */
    public function addMessage(\Instudies\SiteBundle\Entity\Message $sendedMessages)
    {
        $this->sendedMessages[] = $sendedMessages;
    }

    /**
     * Get sendedMessages
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSendedMessages()
    {
        return $this->sendedMessages;
    }

    /**
     * Get recievedMessages
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRecievedMessages()
    {
        return $this->recievedMessages;
    }

    /**
     * Add conversations1
     *
     * @param Instudies\SiteBundle\Entity\Conversation $conversations1
     */
    public function addConversation(\Instudies\SiteBundle\Entity\Conversation $conversations1)
    {
        $this->conversations1[] = $conversations1;
    }

    /**
     * Get conversations1
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getConversations1()
    {
        return $this->conversations1;
    }

    /**
     * Get conversations2
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getConversations2()
    {
        return $this->conversations2;
    }

    /**
     * Add favourites
     *
     * @param Instudies\SiteBundle\Entity\Favourite $favourites
     */
    public function addFavourite(\Instudies\SiteBundle\Entity\Favourite $favourites)
    {
        $this->favourites[] = $favourites;
    }

    /**
     * Get favourites
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFavourites()
    {
        return $this->favourites;
    }

    /**
     * Get myFavourites
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMyFavourites()
    {
        return $this->myFavourites;
    }

    /**
     * Add comments
     *
     * @param Instudies\SiteBundle\Entity\Comment $comments
     */
    public function addComment(\Instudies\SiteBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add recievedNotifications
     *
     * @param Instudies\SiteBundle\Entity\Notification $recievedNotifications
     */
    public function addNotification(\Instudies\SiteBundle\Entity\Notification $recievedNotifications)
    {
        $this->recievedNotifications[] = $recievedNotifications;
    }

    /**
     * Get recievedNotifications
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRecievedNotifications()
    {
        return $this->recievedNotifications;
    }

    /**
     * Get sendedNotifications
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSendedNotifications()
    {
        return $this->sendedNotifications;
    }

    /**
     * Add invitations
     *
     * @param Instudies\SiteBundle\Entity\Invitation $invitations
     */
    public function addInvitation(\Instudies\SiteBundle\Entity\Invitation $invitations)
    {
        $this->invitations[] = $invitations;
    }

    /**
     * Get invitations
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getInvitations()
    {
        return $this->invitations;
    }

    /**
     * Add joinEducationGroupRequests
     *
     * @param Instudies\SiteBundle\Entity\JoinEducationGroupRequest $joinEducationGroupRequests
     */
    public function addJoinEducationGroupRequest(\Instudies\SiteBundle\Entity\JoinEducationGroupRequest $joinEducationGroupRequests)
    {
        $this->joinEducationGroupRequests[] = $joinEducationGroupRequests;
    }

    /**
     * Get joinEducationGroupRequests
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getJoinEducationGroupRequests()
    {
        return $this->joinEducationGroupRequests;
    }

    /**
     * Add educationGroupBlogPosts
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupBlogPost $educationGroupBlogPosts
     */
    public function addEducationGroupBlogPost(\Instudies\SiteBundle\Entity\EducationGroupBlogPost $educationGroupBlogPosts)
    {
        $this->educationGroupBlogPosts[] = $educationGroupBlogPosts;
    }

    /**
     * Get educationGroupBlogPosts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEducationGroupBlogPosts()
    {
        return $this->educationGroupBlogPosts;
    }

    /**
     * Add educationGroupEventPosts
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupEventPost $educationGroupEventPosts
     */
    public function addEducationGroupEventPost(\Instudies\SiteBundle\Entity\EducationGroupEventPost $educationGroupEventPosts)
    {
        $this->educationGroupEventPosts[] = $educationGroupEventPosts;
    }

    /**
     * Get educationGroupEventPosts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEducationGroupEventPosts()
    {
        return $this->educationGroupEventPosts;
    }

    /**
     * Add educationGroupHomeworkPosts
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupHomeworkPost $educationGroupHomeworkPosts
     */
    public function addEducationGroupHomeworkPost(\Instudies\SiteBundle\Entity\EducationGroupHomeworkPost $educationGroupHomeworkPosts)
    {
        $this->educationGroupHomeworkPosts[] = $educationGroupHomeworkPosts;
    }

    /**
     * Get educationGroupHomeworkPosts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEducationGroupHomeworkPosts()
    {
        return $this->educationGroupHomeworkPosts;
    }

    /**
     * Add educationGroupSummaryPosts
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupSummaryPost $educationGroupSummaryPosts
     */
    public function addEducationGroupSummaryPost(\Instudies\SiteBundle\Entity\EducationGroupSummaryPost $educationGroupSummaryPosts)
    {
        $this->educationGroupSummaryPosts[] = $educationGroupSummaryPosts;
    }

    /**
     * Get educationGroupSummaryPosts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEducationGroupSummaryPosts()
    {
        return $this->educationGroupSummaryPosts;
    }

    /**
     * Add readedComment
     *
     * @param Instudies\SiteBundle\Entity\ReadedComment $readedComment
     */
    public function addReadedComment(\Instudies\SiteBundle\Entity\ReadedComment $readedComment)
    {
        $this->readedComment[] = $readedComment;
    }

    /**
     * Get readedComment
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getReadedComment()
    {
        return $this->readedComment;
    }

    /**
     * Add readedEducationGroupBlogPost
     *
     * @param Instudies\SiteBundle\Entity\ReadedEducationGroupBlogPost $readedEducationGroupBlogPost
     */
    public function addReadedEducationGroupBlogPost(\Instudies\SiteBundle\Entity\ReadedEducationGroupBlogPost $readedEducationGroupBlogPost)
    {
        $this->readedEducationGroupBlogPost[] = $readedEducationGroupBlogPost;
    }

    /**
     * Get readedEducationGroupBlogPost
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getReadedEducationGroupBlogPost()
    {
        return $this->readedEducationGroupBlogPost;
    }

    /**
     * Add unreadedEducationGroupBlogPost
     *
     * @param Instudies\SiteBundle\Entity\UnreadedEducationGroupBlogPost $unreadedEducationGroupBlogPost
     */
    public function addUnreadedEducationGroupBlogPost(\Instudies\SiteBundle\Entity\UnreadedEducationGroupBlogPost $unreadedEducationGroupBlogPost)
    {
        $this->unreadedEducationGroupBlogPost[] = $unreadedEducationGroupBlogPost;
    }

    /**
     * Get unreadedEducationGroupBlogPost
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUnreadedEducationGroupBlogPost()
    {
        return $this->unreadedEducationGroupBlogPost;
    }

    /**
     * Add unreadedEducationGroupHomeworkPost
     *
     * @param Instudies\SiteBundle\Entity\UnreadedEducationGroupHomeworkPost $unreadedEducationGroupHomeworkPost
     */
    public function addUnreadedEducationGroupHomeworkPost(\Instudies\SiteBundle\Entity\UnreadedEducationGroupHomeworkPost $unreadedEducationGroupHomeworkPost)
    {
        $this->unreadedEducationGroupHomeworkPost[] = $unreadedEducationGroupHomeworkPost;
    }

    /**
     * Get unreadedEducationGroupHomeworkPost
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUnreadedEducationGroupHomeworkPost()
    {
        return $this->unreadedEducationGroupHomeworkPost;
    }

    /**
     * Add unreadedEducationGroupSummaryPost
     *
     * @param Instudies\SiteBundle\Entity\UnreadedEducationGroupSummaryPost $unreadedEducationGroupSummaryPost
     */
    public function addUnreadedEducationGroupSummaryPost(\Instudies\SiteBundle\Entity\UnreadedEducationGroupSummaryPost $unreadedEducationGroupSummaryPost)
    {
        $this->unreadedEducationGroupSummaryPost[] = $unreadedEducationGroupSummaryPost;
    }

    /**
     * Get unreadedEducationGroupSummaryPost
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUnreadedEducationGroupSummaryPost()
    {
        return $this->unreadedEducationGroupSummaryPost;
    }

    /**
     * Add unreadedEducationGroupEventPost
     *
     * @param Instudies\SiteBundle\Entity\UnreadedEducationGroupEventPost $unreadedEducationGroupEventPost
     */
    public function addUnreadedEducationGroupEventPost(\Instudies\SiteBundle\Entity\UnreadedEducationGroupEventPost $unreadedEducationGroupEventPost)
    {
        $this->unreadedEducationGroupEventPost[] = $unreadedEducationGroupEventPost;
    }

    /**
     * Get unreadedEducationGroupEventPost
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUnreadedEducationGroupEventPost()
    {
        return $this->unreadedEducationGroupEventPost;
    }

    /**
     * Add readedActivity
     *
     * @param Instudies\SiteBundle\Entity\ReadedActivity $readedActivity
     */
    public function addReadedActivity(\Instudies\SiteBundle\Entity\ReadedActivity $readedActivity)
    {
        $this->readedActivity[] = $readedActivity;
    }

    /**
     * Get readedActivity
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getReadedActivity()
    {
        return $this->readedActivity;
    }

    /**
     * Add unreadedActivity
     *
     * @param Instudies\SiteBundle\Entity\UnreadedActivity $unreadedActivity
     */
    public function addUnreadedActivity(\Instudies\SiteBundle\Entity\UnreadedActivity $unreadedActivity)
    {
        $this->unreadedActivity[] = $unreadedActivity;
    }

    /**
     * Get unreadedActivity
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUnreadedActivity()
    {
        return $this->unreadedActivity;
    }

    /**
     * Add feedbacks
     *
     * @param Instudies\SiteBundle\Entity\Feedback $feedbacks
     */
    public function addFeedback(\Instudies\SiteBundle\Entity\Feedback $feedbacks)
    {
        $this->feedbacks[] = $feedbacks;
    }

    /**
     * Get feedbacks
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    /**
     * Add notifiedEducationGroupBlogPost
     *
     * @param Instudies\SiteBundle\Entity\NotifiedEducationGroupBlogPost $notifiedEducationGroupBlogPost
     */
    public function addNotifiedEducationGroupBlogPost(\Instudies\SiteBundle\Entity\NotifiedEducationGroupBlogPost $notifiedEducationGroupBlogPost)
    {
        $this->notifiedEducationGroupBlogPost[] = $notifiedEducationGroupBlogPost;
    }

    /**
     * Get notifiedEducationGroupBlogPost
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getNotifiedEducationGroupBlogPost()
    {
        return $this->notifiedEducationGroupBlogPost;
    }

    /**
     * Set notifiedUnactiveGroupCreatorFirstTime
     *
     * @param boolean $notifiedUnactiveGroupCreatorFirstTime
     */
    public function setNotifiedUnactiveGroupCreatorFirstTime($notifiedUnactiveGroupCreatorFirstTime)
    {
        $this->notifiedUnactiveGroupCreatorFirstTime = $notifiedUnactiveGroupCreatorFirstTime;
    }

    /**
     * Get notifiedUnactiveGroupCreatorFirstTime
     *
     * @return boolean 
     */
    public function getNotifiedUnactiveGroupCreatorFirstTime()
    {
        return $this->notifiedUnactiveGroupCreatorFirstTime;
    }

    /**
     * Set notifiedUnactiveGroupCreatorSecondTime
     *
     * @param boolean $notifiedUnactiveGroupCreatorSecondTime
     */
    public function setNotifiedUnactiveGroupCreatorSecondTime($notifiedUnactiveGroupCreatorSecondTime)
    {
        $this->notifiedUnactiveGroupCreatorSecondTime = $notifiedUnactiveGroupCreatorSecondTime;
    }

    /**
     * Get notifiedUnactiveGroupCreatorSecondTime
     *
     * @return boolean 
     */
    public function getNotifiedUnactiveGroupCreatorSecondTime()
    {
        return $this->notifiedUnactiveGroupCreatorSecondTime;
    }

    /**
     * Set registrationReferrer
     *
     * @param text $registrationReferrer
     */
    public function setRegistrationReferrer($registrationReferrer)
    {
        $this->registrationReferrer = $registrationReferrer;
    }

    /**
     * Get registrationReferrer
     *
     * @return text 
     */
    public function getRegistrationReferrer()
    {
        return $this->registrationReferrer;
    }
}