- **Bean\Bundle\BookBundle\Doctrine\Orm**
 
 `<many-to-one field="book" target-entity="Bean\Bundle\BookBundle\Doctrine\Orm\Book">`     
`<join-column name="id_book" referenced-column-name="id" nullable="false"/>`   
`</many-to-one>`
- Need to point to the **entity** class **Bean\Bundle\BookBundle\Doctrine\Orm\Book** or api-platform will **incorrectly pull Table name** from its mapped-superclass BookModel


- This will not work on a **orm-superclass** since the inverse side is illegal

  `<many-to-one field="partOf" target-entity="Chapter" inversed-by="parts">
                        <join-column name="id_chapter" referenced-column-name="id" />
                </many-to-one>`
                
  `<one-to-many field="parts" target-entity="Chapter" mapped-by="partOf" />`
