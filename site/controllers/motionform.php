<?php

/**
 * @version    1.4.04
 * @package    Com_Gavoting
 * @author     Glenn Arkell <glenn@glennarkell.com.au>
 * @copyright  2020 Glenn Arkell
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Session\Session;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\MVC\Controller\FormController;

/**
 * Motions controller class.
 *
 * @since  1.6
 */
class GavotingControllerMotionForm extends FormController
{
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 * @return void
	 * @since    1.6
     * @throws Exception
	 */
	public function edit($key = NULL, $urlVar = NULL)
	{
		$app = Factory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_gavoting.edit.motion.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the record id for the user to edit in the session.
		$app->setUserState('com_gavoting.edit.motion.id', $editId);

		// Get the model.
		$model = $this->getModel('MotionForm', 'GavotingModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(Route::_('index.php?option=com_gavoting&view=motionform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 * @return void
	 * @throws Exception
	 * @since  1.6
	 */
	public function save($key = NULL, $urlVar = NULL)
	{
		// Check for request forgeries.
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app   = Factory::getApplication();
		$model = $this->getModel('MotionForm', 'GavotingModel');

		// Get the submitted data.
		$data = Factory::getApplication()->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();

		if (!$form) {
			throw new Exception($model->getError(), 500);
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false) {
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$input = $app->input;
			$jform = $input->get('jform', array(), 'ARRAY');

			// Save the data in the session.
			$app->setUserState('com_gavoting.edit.motion.data', $jform);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_gavoting.edit.motion.id');
			$this->setRedirect(Route::_('index.php?option=com_gavoting&view=motionform&layout=edit&id=' . $id, false));

			$this->redirect();
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_gavoting.edit.motion.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_gavoting.edit.motion.id');
			$this->setMessage(Text::sprintf(Text::_('COM_GAVOTING_SAVE_FAILED'), $model->getError()), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_gavoting&view=motionform&layout=edit&id=' . $id, false));
		}

		// Check in the record.
		if ($return) {
			$model->checkin($return);
		}
		$this->setMessage(Text::_('COM_GAVOTING_ITEM_SAVED_SUCCESSFULLY'), 'success');

		// Clear the record id from the session.
		$app->setUserState('com_gavoting.edit.motion.id', null);

		// Redirect to the list screen.
		$this->setRedirect(Route::_('index.php?option=com_gavoting&view=motions', false));

		// Flush the data from the session.
		$app->setUserState('com_gavoting.edit.motion.data', null);
	}

	/**
	 * Method to abort current operation
	 * @return void
	 * @throws Exception
	 */
	public function cancel($key = NULL)
	{
		$app = Factory::getApplication();

		// Get the current edit id.
		$editId = (int) $app->getUserState('com_gavoting.edit.motion.id');

		// Get the model.
		$model = $this->getModel('MotionForm', 'GavotingModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$this->setRedirect(Route::_('index.php?option=com_gavoting&view=motions', false));
	}

	/**
	 * Method to remove data
	 * @return void
	 * @throws Exception
     * @since 1.6
	 */
	public function remove()
    {
        $app   = Factory::getApplication();
        $model = $this->getModel('MotionForm', 'GavotingModel');
        $pk    = $app->input->getInt('id');

        // Attempt to update the data
        try
        {
            $return = $model->delete($pk);

            // Check in the record
            $model->checkin($return);

            // Clear the record id from the session.
            $app->setUserState('com_gavoting.edit.motion.id', null);

            $menu = $app->getMenu();
            $item = $menu->getActive();
            $url = (empty($item->link) ? 'index.php?option=com_gavoting&view=motions' : $item->link);

            // Redirect to the list screen
            $this->setMessage(Text::_('COM_GAVOTING_ITEM_DELETED_SUCCESSFULLY'), 'success');
            $this->setRedirect(Route::_($url, false));

            // Flush the data from the session.
            $app->setUserState('com_gavoting.edit.motion.data', null);
        }
        catch (Exception $e)
        {
            $errorType = ($e->getCode() == '404') ? Text::_('ERROR') : 'warning';
            $this->setMessage($e->getMessage(), $errorType);
            $this->setRedirect('index.php?option=com_gavoting&view=motions');
        }
    }

	/**
	 * Method to archive data
	 * @return void
	 * @throws Exception
     * @since 1.6
	 */
	public function archive()
    {
        $app   = Factory::getApplication();
        $model = $this->getModel('MotionForm', 'GavotingModel');
        $pk    = $app->input->getInt('id');

        // Attempt to update the data
        try
        {
            $return = $model->archive($pk);

            // Check in the record
            $model->checkin($return);

            // Clear the record id from the session.
            $app->setUserState('com_gavoting.edit.motion.id', null);

            $menu = $app->getMenu();
            $item = $menu->getActive();
            $url = (empty($item->link) ? 'index.php?option=com_gavoting&view=motions' : $item->link);

            // Redirect to the list screen
            $this->setMessage(Text::_('COM_GAVOTING_ITEM_ARCHIVED_SUCCESSFULLY'), 'success');
            $this->setRedirect(Route::_($url, false));

            // Flush the data from the session.
            $app->setUserState('com_gavoting.edit.motion.data', null);
        }
        catch (Exception $e)
        {
            $errorType = ($e->getCode() == '404') ? Text::_('ERROR') : 'warning';
            $this->setMessage($e->getMessage(), $errorType);
            $this->setRedirect('index.php?option=com_gavoting&view=motions');
        }
    }

	/**
	 * Method to record a vote for motion
	 * @return void
	 */
	public function voteFor()
    {
        $app   = Factory::getApplication();
        $model = $this->getModel('MotionForm', 'GavotingModel');
        $pk    = $app->input->getInt('id');

        // Attempt to update the data
        try
        {
            $return = $model->voteFor($pk);

            // Clear the record id from the session.
            $app->setUserState('com_gavoting.edit.motion.id', null);

            // Redirect to the list screen
            $this->setMessage(Text::_('COM_GAVOTING_ITEM_SAVED_SUCCESSFULLY'), 'success');
            $this->setRedirect(Route::_('index.php?option=com_gavoting&view=motions', false));

            // Flush the data from the session.
            $app->setUserState('com_gavoting.edit.motion.data', null);
        }
        catch (Exception $e)
        {
            $errorType = ($e->getCode() == '404') ? Text::_('ERROR') : 'warning';
            $this->setMessage($e->getMessage(), $errorType);
            $this->setRedirect('index.php?option=com_gavoting&view=motions');
        }
    }

	/**
	 * Method to record a vote against motion
	 * @return void
	 */
	public function voteAgainst()
    {
        $app   = Factory::getApplication();
        $model = $this->getModel('MotionForm', 'GavotingModel');
        $pk    = $app->input->getInt('id');

        // Attempt to update the data
        try
        {
            $return = $model->voteAgainst($pk);

            // Clear the record id from the session.
            $app->setUserState('com_gavoting.edit.motion.id', null);

            // Redirect to the list screen
            $this->setMessage(Text::_('COM_GAVOTING_ITEM_SAVED_SUCCESSFULLY'), 'success');
            $this->setRedirect(Route::_('index.php?option=com_gavoting&view=motions', false));

            // Flush the data from the session.
            $app->setUserState('com_gavoting.edit.motion.data', null);
        }
        catch (Exception $e)
        {
            $errorType = ($e->getCode() == '404') ? Text::_('ERROR') : 'warning';
            $this->setMessage($e->getMessage(), $errorType);
            $this->setRedirect('index.php?option=com_gavoting&view=motions');
        }
    }

	/**
	 * Method to record a vote decision motion
	 * @return void
	 */
	public function voteDecision()
    {
        $app   = Factory::getApplication();
        $model = $this->getModel('MotionForm', 'GavotingModel');
        $pk    = $app->input->getInt('id');

        // Attempt to update the data
        try
        {
            $return = $model->voteDecision($pk);

            // Clear the record id from the session.
            $app->setUserState('com_gavoting.edit.motion.id', null);

            // Redirect to the list screen
            $this->setMessage(Text::_('COM_GAVOTING_DECISION_SUCCESSFULLY'), 'success');
            $this->setRedirect(Route::_('index.php?option=com_gavoting&view=motions', false));

            // Flush the data from the session.
            $app->setUserState('com_gavoting.edit.motion.data', null);
        }
        catch (Exception $e)
        {
            $errorType = ($e->getCode() == '404') ? Text::_('ERROR') : 'warning';
            $this->setMessage($e->getMessage(), $errorType);
            $this->setRedirect('index.php?option=com_gavoting&view=motions');
        }
    }
}
