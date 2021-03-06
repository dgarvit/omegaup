<?php

include_once('base/Problemset_Identities.dao.base.php');
include_once('base/Problemset_Identities.vo.base.php');
/** ProblemsetIdentities Data Access Object (DAO).
  *
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para
  * almacenar de forma permanente y recuperar instancias de objetos {@link ProblemsetIdentities }.
  * @access public
  *
  */
class ProblemsetIdentitiesDAO extends ProblemsetIdentitiesDAOBase {
    public static function CheckAndSaveFirstTimeAccess(
        $identity_id,
        $problemset_id,
        $grant_access = false,
        $share_user_information = false
    ) {
        $problemset_identity = self::getByPK($identity_id, $problemset_id);
        if (is_null($problemset_identity)) {
            if (!$grant_access) {
                // User was not authorized to do this.
                throw new ForbiddenAccessException();
            }
            $problemset_identity = new ProblemsetIdentities();
            $problemset_identity->identity_id = $identity_id;
            $problemset_identity->problemset_id = $problemset_id;
            $problemset_identity->access_time = date('Y-m-d H:i:s');
            $problemset_identity->score = 0;
            $problemset_identity->time = 0;
            $problemset_identity->share_user_information = $share_user_information;
            ProblemsetIdentitiesDAO::save($problemset_identity);
        } elseif (is_null($problemset_identity->access_time)) {
            // If its set to default time, update it
            $problemset_identity->access_time = date('Y-m-d H:i:s');
            $problemset_identity->share_user_information = $share_user_information;
            ProblemsetIdentitiesDAO::save($problemset_identity);
        }
        return $problemset_identity;
    }
}
