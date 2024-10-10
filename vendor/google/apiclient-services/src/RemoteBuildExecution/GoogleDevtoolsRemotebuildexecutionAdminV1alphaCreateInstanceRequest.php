<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\RemoteBuildExecution;

class GoogleDevtoolsRemotebuildexecutionAdminV1alphaCreateInstanceRequest extends \Google\Model
{
  protected $instanceType = GoogleDevtoolsRemotebuildexecutionAdminV1alphaInstance::class;
  protected $instanceDataType = '';
  public $instanceId;
  public $parent;

  /**
   * @param GoogleDevtoolsRemotebuildexecutionAdminV1alphaInstance
   */
  public function setInstance(GoogleDevtoolsRemotebuildexecutionAdminV1alphaInstance $instance)
  {
    $this->instance = $instance;
  }
  /**
   * @return GoogleDevtoolsRemotebuildexecutionAdminV1alphaInstance
   */
  public function getInstance()
  {
    return $this->instance;
  }
  public function setInstanceId($instanceId)
  {
    $this->instanceId = $instanceId;
  }
  public function getInstanceId()
  {
    return $this->instanceId;
  }
  public function setParent($parent)
  {
    $this->parent = $parent;
  }
  public function getParent()
  {
    return $this->parent;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleDevtoolsRemotebuildexecutionAdminV1alphaCreateInstanceRequest::class, 'Google_Service_RemoteBuildExecution_GoogleDevtoolsRemotebuildexecutionAdminV1alphaCreateInstanceRequest');