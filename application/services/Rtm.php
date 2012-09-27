<?php

/**
 * This class provides interaction with rememberthemilk services.
 * ToDo: This class holds data and logic, separate them (see: data folder)
 * ToDo: Zend Framework once had a model to communicate 
 *          with the Rtm api, look it up.
 * 
 * @author Jeroen Franse <jroenf@github>
 */
class Application_Service_Rtm {
    /**
     * These are the default lists
     * @var array 
     */
    protected $_atoms_lists = array(
'Inbox'     =>  'http://www.rememberthemilk.com/atom/roenf/21864645/?tok=eJwNwgsKAjEMBcATFbJpPu1xmuQFBFFQ7487TIm1LlY78PKKWDVbs4SIxavDuxEnTO1OxuodCd1gk3QZnzdePZ6P72-wtUxMdMCJeYH7VJH0nBeTm**cWlt4Ke5EyCwIihEXiVJrb*zJ8gdpCSmw',
'Personal'  =>  'http://www.rememberthemilk.com/atom/roenf/21864646/?tok=eJwNi0sKAzEMxU4UyHhsP*c48Q8KpYW296dBWmijZG0xEt2FRLpb3i2RPCcxsh3d5dtV9DiVBO1RsoqUAzw*73r1eD6*v0GXKR8GoRmTNsIu1kBXU51JT6RXxF5sdy5IowweefEKh6pSGOoPl58q-g',
'Study'     =>  'http://www.rememberthemilk.com/atom/roenf/21864647/?tok=eJwNwgsKAkEIANATDajjb4*jo8JCFFT3px6vWEecRKOtrDK99sgpBiC2mrSZzkgV-QclscnTcjUpH*P1fvVz1uP*fBehKyvbQve*avrqY7J7cw9VbhLoimhXNCQMVhNgQDro5sdCOS0T5Ad**imu',
'Work'      =>  'http://www.rememberthemilk.com/atom/roenf/21864648/?tok=eJwNxkkKAjEQAMAXDXR6z3PSGwiioP4fB*pQxTriKHrayirCi0ayGADZasJmOk6o6A0UxSayZTcqp-H1efdrrufj*7twubKyX0YBzWiDZ5Ur1k6q2k5njAgC8q70wlpxwDdtX4m2BrCTxv9tmCmn',
'Sent'      =>  'http://www.rememberthemilk.com/atom/roenf/21864649/?tok=eJwNwgGKAyAMBMAXCRqzcfOcaAwUSgtt-8-dMKlWoMDirly5N3MWTmrvoitrr6q7YxvsfzfBqn0u-IrpWdo*7-uq9nx8f00GTU29gQFHjB4TwZkkB5IgOo3LzMPkDk-6pYwjrsdQmLlpfcw-QC4oXA',
);

    
    /**
     * These are my personalized smart lists.
     * @var array 
     */
    protected $_atoms_smartlists = array(
'-dueMonth'         =>  'http://www.rememberthemilk.com/atom/roenf/21872293/?tok=eJwNzFkKAjAMANETFdI0S3OcZgNBFNT7Y2E*H5MkzRtZTmlquu9czZEEgKTZrt3lx4XlBoKs7VFshUKhND7vevV4Pr6-gXMroq0RgbhgeqnYaWG9WwRyMJFlDn6a4tDaoMfKM2dcx0He0y3wD3Z-Ko0',
'-dueWeek'          =>  'http://www.rememberthemilk.com/atom/roenf/21872269/?tok=eJwNwgEKAzEIBMAXBRKjrnmOngoHpYW2-6cdJllbjES9kMgIy91yJc9JjOxAd4WHiv5PJUHHVXKKlC-weL-q2eNxf76DloFIz4DgtEfueYwMSLYtGrk8J-lCS2FKEYXzUUtSTevCqtW2W39jNCmd',
'-TodoToday'        =>  'http://www.rememberthemilk.com/atom/roenf/21871558/?tok=eJwNy1EKAzEIBcATLRjjU3McExUKpYW296cL8zsp2nCGRlla7u05GyeFiMWyt3XXjq3QGynDep-CKlY5JtfnXa**no-v7*LhNgC-akBFJ*MeBoSsxWvmiNkr6xRNSSOaAR-ufIQwyTRMveKU-wE5ISiZ',
'@computer'         =>  'http://www.rememberthemilk.com/atom/roenf/21871887/?tok=eJwNzUsKAjAMANETFZo03*M0TQKCKKj3x8Ks3yRJsyHLLk3NCMvVfJLmRNLs0O6KHcJym4KsHafYC4WO0vi869Xj*fj*BoIpmOmoTX2sYMMONwm0jSbdYTLLJ3Qyk7WegHuE68g64rwg3Z38D5YpKjA',
'@home'             =>  'http://www.rememberthemilk.com/atom/roenf/21872019/?tok=eJwNwomJAjAQAMCKApv9U072A0EU1P65G6ZYRxxFb1tZRXjRSBYDIFtN2EzHDRX9D4piE9lyGpXTeH3e-Zr1fHx-C7cbwj7rNjWcHkimId6nyRtm02nD0xyRKHJVJPc4h-YOFr-FEkacf32-Kgw',
'@mall'             =>  'http://www.rememberthemilk.com/atom/roenf/21871623/?tok=eJwNy8kJAzEMAMCKDDosyS7HuiAQdiFJ-2RhvpNTWxaJnrK0dF-JLZETgKZlu3WXH1fRByiJtUfJLtIZNsfnrqvH*-X9DcJlqMSjgo2wa0liRQIePoAStrfLVl51vIhrbwXOIMHUmpFPAwjiP36KKfw',
'@phone'            =>  'http://www.rememberthemilk.com/atom/roenf/21872021/?tok=eJwNxYkJAjEQAMCKAtl-U87tB4IoqP3jwcAU64ij6NVWVhFeNJLFeyNbTdhMxxUqetuKYhPZchqV03h93v2a9Xx8fwvBDTfCKvOypG4fjIsC8748VRhEtGPokGdjAMW0GlQyuTHoSWz7A5e*KnM',
'@somewhere'        =>  'http://www.rememberthemilk.com/atom/roenf/21872022/?tok=eJwNzFkKAjEQRdEVBSrVqSHLSQ0PBFFQ948N9-fcWgpxFj1tZRXhdUGyFhEvK4QBHSdU9I6UxRDZspt1pa3xefcL4-n4-gZPNybmoUumIJs03e-nlAOc5KbcbdpJVXDNxHa6GgFg8iVJ0XWbP6QZK7w',
'_projects-active'  =>  'http://www.rememberthemilk.com/atom/roenf/21871702/?tok=eJwNy1sKQjEMBcAVFdLTPHqXkzQJCKKg7h8vzO8ka8uGqJelZcTO1XKSicCWHdZd4aGiN1KIdZySq6B8jMfnXa8ez8f3NzC3TSMM1wQcV8rynOpE6QK9VmiT5ITsY4WerArj6KaOHSu8Jt3rD20eKe8',
'_projects-pending' =>  'http://www.rememberthemilk.com/atom/roenf/21871712/?tok=eJwNwgmKAzAIAMAXBdQYNc-xhEJpoe3-2R2mWOYYHfHW0oqw2nOyGIBYa0JnOjzkyH8QOjqRfW6TcCqvz7tfs56P728RmqIiLXMFyIvK6WE04hft7oBO2eJmLllq2JtbICLSYd-xkoOem-8AcHsqLg',
'AllTasks'          =>  'http://www.rememberthemilk.com/atom/roenf/21864650/?tok=eJwNzFsKQkEIANAVDajX1yxHR4UgCqr904XzfYp1xEk02soq0*saOcUAxFaTNtMZqaI3UBKbPC27SfkYr8*7X7Oej*9vEbqyCizonbwxadqPuPHQRXdlE4BYOycD9dJsl1YWbFYM3DwA3Rx-eZoqCA',
'PendingTasks'      =>  'http://www.rememberthemilk.com/atom/roenf/21872505/?tok=eJwNy1EKAjEMBcATFbJpX5Iep2nzQBAF9f64ML9zhhGhsFV*-GTG6cQ*Q0SHH6aTlSsNdhNTOHMXZqmN7aN93vViez6*v6ZXuELQKgujM7oaInLfhai5J4JzXFa4-KwpoKpluRLC4NIlXZz*B3yuKfo',
'TagMe'             =>  'http://www.rememberthemilk.com/atom/roenf/21871784/?tok=eJwNzEsKAjAMANETFZI0vx6naRIQREG9PxZm*yZZW5xEd1laRnjOlpMMQGzZYd0VO1T0BkpiHadkFSkf4-F516vH8-H9DUI3NOcxzXfgAcJrad03SiIGbi9PbLRZZ0JOIFqyFNDYQWQ2esuKP2quKRE',
);



    public function getAllListNames() {
        return array_keys($this->_atoms_lists)+array_keys($this->_atoms_smartlists);
    }
    
    public function getListNames() {
        return array_keys($this->_atoms_lists);
    }
    
    public function getSmartlistNames() {
        return array_keys($this->_atoms_smartlists);
    }
    
    public function getTasksFromList($listId){
       $feedUrl = (array_key_exists($listId, $this->_atoms_lists))?
               $this->_atoms_lists[$listId]:$this->_atoms_smartlists[$listId];
       $atomfeed = new Zend_Feed_Atom($feedUrl);
       return $atomfeed;
    }
    
    
}
