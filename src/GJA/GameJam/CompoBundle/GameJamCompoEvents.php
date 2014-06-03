<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\CompoBundle;

final class GameJamCompoEvents
{
    const ACTIVITY = "gamejam.compo.activity";

    const TEAM_INVITATION = "gamejam.compo.team_invitation";
    const TEAM_REQUEST = "gamejam.compo.team_request";

    const TEAM_INVITATION_ACCEPTED = "gamejam.compo.team_invitation.accepted";
    const TEAM_REQUEST_ACCEPTED = "gamejam.compo.team_request.accepted";

    const TEAM_INVITATION_REJECTED = "gamejam.compo.team_invitation.rejected";
    const TEAM_REQUEST_REJECTED = "gamejam.compo.team_request.rejected";

    const TEAM_INVITATION_CANCELLED = "gamejam.compo.team_invitation.cancelled";
    const TEAM_REQUEST_CANCELLED = "gamejam.compo.team_request.cancelled";
} 