[ROAD MAP](roadmap/plan.md)
- When on Class Table Inheritance, We have to delete dev-book\libraries\bundle\BeanBookBundle\src\Resources\config\
    - **doctrine-model\orm-class\Book.orm.xml**
    - and **doctrine-model\orm-class\Chapter.orm.xml**
     
=> Get `[Doctrine\ORM\Mapping\MappingException] It is illegal to put an inverse side one-to-many or many-to-many association on mapped superclass`  Book/Chapter