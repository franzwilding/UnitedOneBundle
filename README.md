# UnitedOneBundle

![Codeship Status](https://codeship.com/projects/428f98d0-b082-0132-9d4e-3a7a9fb44a4e/status?branch=master)

Default theme for the Symfony2 Content Management System United CMS. 

# Installation

Install symfony
    
    composer create-project symfony/framework-standard-edition
        
Install UnitedCore and a theme (UnitedOne, the default theme, requires UnitedCore so we don't need to)
    
    composer require franzwilding/united-one-bundle
    
Register United bundles 
    
    new United\CoreBundle\UnitedCoreBundle(),
    new United\OneBundle\UnitedOneBundle(), 

**Check out the Getting started tutorial (Comming soon)**