- **Bean\Bundle\OrganizationBundle\Doctrine\Orm**
 
 `<many-to-one field="Organization" target-entity="Bean\Bundle\OrganizationBundle\Doctrine\Orm\Organization">`     
`<join-column name="id_Organization" referenced-column-name="id" nullable="false"/>`   
`</many-to-one>`
- Need to point to the **entity** class **Bean\Bundle\OrganizationBundle\Doctrine\Orm\Organization** or api-platform will **incorrectly pull Table name** from its mapped-superclass OrganizationModel


- This will not work on a **orm-superclass** since the inverse side is illegal

  `<many-to-one field="partOf" target-entity="Chapter" inversed-by="parts">
                        <join-column name="id_chapter" referenced-column-name="id" />
                </many-to-one>`
                
  `<one-to-many field="parts" target-entity="Chapter" mapped-by="partOf" />`
