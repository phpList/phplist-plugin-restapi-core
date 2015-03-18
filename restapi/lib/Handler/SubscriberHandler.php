<?php

namespace Rapi\Handler;

class SubscriberHandler
 {
     public function __construct( \phpList\Entity\SubscriberEntity $scrEntity, \phpList\SubscriberManager $subscriberManager )
     {
         $this->scrEntity = $scrEntity;
         $this->subscriberManager = $subscriberManager;
     }

     /**
      * Insert a new subscriber
      * @return int ID of new subscriber
      */
     public function add(
        $emailAddress
        , $blacklisted = 0
        , $bounceCount = 0
        , $confirmed = 1
        , $disabled = 0
        , $encPass = ""
        , $entered = ""
        , $extraData = ""
        , $foreigKkey = ""
        , $htmlEmail = 1
        , $modified = ""
        , $optedIn = 0
        , $passwordChanged = ""
        , $plainPass = ""
        , $plainPasschanged = ""
        , $rssFrequency = ""
        , $subscribePage = ""
    )
     {
        // Mak an array of all those function arguments for easier handling
        $argsArray = get_defined_vars();

        // Loop through each function argument
        foreach ( $argsArray as $key => $value ) {
            // Assign the correct value to each of the Entity properties
            $this->scrEntity->$key = $value;
        }

        // Insert the subscriber & return
        return $this->subscriberManager->add( $this->scrEntity );

     }

     public function getById( $id )
     {
        // Get the subscriber & return
         return $this->subscriberManager->getSubscriberById( $id );
     }

}
