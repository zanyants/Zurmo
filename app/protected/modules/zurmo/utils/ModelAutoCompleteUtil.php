<?php
    /*********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2011 Zurmo Inc.
     *
     * Zurmo is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY ZURMO, ZURMO DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * Zurmo is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
     * details.
     *
     * You should have received a copy of the GNU General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact Zurmo, Inc. with a mailing address at 113 McHenry Road Suite 207,
     * Buffalo Grove, IL 60089, USA. or at email address contact@zurmo.com.
     ********************************************************************************/

    /**
     * Helper class to convert a model search into
     * an Jui AutoComplete ready array.  There are
     * three types of searches, Generic, User, and Person.
     * Person and User utilize fullName instead of name
     * while User adds the additional usage of username
     * in the resulting label
     */
    class ModelAutoCompleteUtil
    {
        /**
         * @return array - Jui AutoComplete ready array
         *  containing id, value, and label elements.
         */
        public static function getByPartialName($modelClassName, $partialName, $pageSize)
        {
            assert('is_string($modelClassName)');
            assert('is_string($partialName)');
            assert('is_int($pageSize)');
            if ($modelClassName == 'User')
            {
                return ModelAutoCompleteUtil::getUserResults($partialName, $pageSize);
            }
            elseif ($modelClassName == 'Contact')
            {
                throw new NotSupportedException();
            }
            elseif ($modelClassName == 'Person')
            {
                throw new NotImplementedException();
            }
            else
            {
                return ModelAutoCompleteUtil::getGenericResults($modelClassName, $partialName, $pageSize);
            }
        }

        protected static function getGenericResults($modelClassName, $partialName, $pageSize)
        {
            $autoCompleteResults = array();
            $models = $modelClassName::getSubset(null, null, $pageSize, "name like lower('{$partialName}%')", 'name');
            foreach ($models as $model)
            {
                $autoCompleteResults[] = array(
                    'id'    => $model->id,
                    'value' => strval($model),
                    'label' => strval($model)
                );
            }
            return $autoCompleteResults;
        }

        protected static function getUserResults($partialName, $pageSize)
        {
            $autoCompleteResults  = array();
            $users                = UserModelSearch::getUsersByPartialFullName($partialName, $pageSize);
            foreach ($users as $user)
            {
                $autoCompleteResults[] = array(
                    'id'    => $user->id,
                    'value' => strval($user),
                    'label' => strval($user) .' (' . $user->username . ')'
                );
            }
            return $autoCompleteResults;
        }
    }
?>