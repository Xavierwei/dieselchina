<?php

class myUser extends SSOSecurityUser
{
  public function getUserStoreId()
  {
    try
    {
      $client = CoreClient::createCoreClient();
      $userProfile = $client->userGetProfile($this->getUuid());
      if ($userProfile)
      {
        $userStoreId = $userProfile->getElement()->getElement()->getStore_Id();
        return $userStoreId;
      }
    }
    catch(Exception $e)
    {
      return false;
    }
  }
}
