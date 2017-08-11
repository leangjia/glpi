<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2017 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

namespace tests\units;

use \DbTestCase;

/* Test for inc/entity.class.php */

class Entity extends DbTestCase {

   public function testSonsAncestors() {
      $ent0 = getItemByTypeName('Entity', '_test_root_entity');
      $this->string($ent0->getField('completename'))
         ->isIdenticalTo('Root entity > _test_root_entity');

      $ent1 = getItemByTypeName('Entity', '_test_child_1');
      $this->string($ent1->getField('completename'))
         ->isIdenticalTo('Root entity > _test_root_entity > _test_child_1');

      $ent2 = getItemByTypeName('Entity', '_test_child_2');
      $this->string($ent2->getField('completename'))
         ->isIdenticalTo('Root entity > _test_root_entity > _test_child_2');

      $this->array(array_keys(getAncestorsOf('glpi_entities', $ent0->getID())))
         ->isIdenticalTo([0]);
      $this->array(array_values(getAncestorsOf('glpi_entities', $ent0->getID())))
         ->isIdenticalTo(['0']);
      $this->array(array_keys(getSonsOf('glpi_entities', $ent0->getID())))
         ->isEqualTo([$ent0->getID(), $ent1->getID(), $ent2->getID()]);
      $this->array(array_values(getSonsOf('glpi_entities', $ent0->getID())))
         ->isIdenticalTo([$ent0->getID(), $ent1->getID(), $ent2->getID()]);

      $this->array(array_keys(getAncestorsOf('glpi_entities', $ent1->getID())))
         ->isEqualTo([0, $ent0->getID()]);
      $this->array(array_values(getAncestorsOf('glpi_entities', $ent1->getID())))
         ->isEqualTo([0, $ent0->getID()]);
      $this->array(array_keys(getSonsOf('glpi_entities', $ent1->getID())))
         ->isEqualTo([$ent1->getID()]);
      $this->array(array_values(getSonsOf('glpi_entities', $ent1->getID())))
         ->isEqualTo([$ent1->getID()]);

      $this->array(array_keys(getAncestorsOf('glpi_entities', $ent2->getID())))
         ->isEqualTo([0, $ent0->getID()]);
      $this->array(array_values(getAncestorsOf('glpi_entities', $ent2->getID())))
         ->isEqualTo([0, $ent0->getID()]);
      $this->array(array_keys(getSonsOf('glpi_entities', $ent2->getID())))
         ->isEqualTo([$ent2->getID()]);
      $this->array(array_values(getSonsOf('glpi_entities', $ent2->getID())))
         ->isEqualTo([$ent2->getID()]);
   }

   public function testPrepareInputForAdd() {
      $entity = new \Entity();

      $this->boolean(
         $entity->prepareInputForAdd([
            'name' => ''
         ])
      )->isFalse();

      $this->boolean(
         $entity->prepareInputForAdd([
            'anykey' => 'anyvalue'
         ])
      )->isFalse();

      $this->array(
         $entity->prepareInputForAdd([
            'name' => 'entname'
         ])
      )
         ->string['name']->isIdenticalTo('entname')
         ->string['completename']->isIdenticalTo('entname')
         ->integer['level']->isIdenticalTo(1)
         ->integer['entities_id']->isIdenticalTo(0);
   }
}
