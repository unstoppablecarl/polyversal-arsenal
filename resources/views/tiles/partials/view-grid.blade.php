<div class="container-fluid">

    <table class="table table-bordered table-condensed tile-grid tile-key" style="width: auto;">
        <tbody>
        <tr>
            <th>Answer Status:</th>
            <td class="danger">Not Started</td>
            <td class="warning">Not Done</td>
            <td class="success">Done</td>
        </tr>
        </tbody>
    </table>

    @foreach($sections as $section)
        <table class="table table-bordered tile-grid">
            <thead>
            @if($contributorTeamCount || $reviewerTeamCount)
                <tr>
                    <th>
                        Section
                        @if($userTileRoleId == \App\Services\Tile\TileRoles::CONTRIBUTOR_ID)
                            @cannot('view', $section['section'])
                                <span class="text-danger" data-tooltip
                                      title="You do not have access to this Tile Section.">
                                    <i class="fa fa-warning"></i>
                                </span>
                            @endcannot
                        @endif
                    </th>

                    @if($contributorTeamCount)
                        <th colspan="{{$contributorTeamCount}}">
                            Contributor Team
                        </th>
                    @endif

                    @if($reviewerTeamCount)
                        <th colspan="{{$reviewerTeamCount}}">
                            Reviewer Team
                        </th>
                    @endif

                </tr>
            @endif
            <tr>
                <th class="col-md-2">{{$section['template_section']->title}}</th>

                @foreach($headers as $headerUser)
                    <?php
                    $headerUserName = $headerUser['user']->name;
                    $headerUserRoleId = $headerUser['tile_role_id'];
                    $headerUserRoleDisplayName = $headerUser['tile_role_display_name'];
                    $userHasColumnAccess = $headerUser['user_has_access'];
                    $userIsColumnOwner = $headerUser['user_is_column_owner'];
                    ?>
                    <th class="col-md-1">
                        {{$headerUserName}}
                        @if($userIsColumnOwner)
                            <span class="text-primary">
                            (You)
                            </span>
                        @endif
                        <div class="tile-role">
                            {{$headerUserRoleDisplayName}}
                            @if(!$userHasColumnAccess)
                                <span class="text-danger" data-tooltip
                                      title="As a Contributor Team member with Tile Role: '{{$item->userTileRoleDisplayName(Auth::user())}}', you can never see this User's Answers">
                                    <i class="fa fa-warning"></i>
                                </span>
                            @endif
                            @include('records.users.controls.buttons',['item' => $headerUser['user'], 'size' => 'sm'])
                        </div>
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($section['rows'] as $row)
                <?php
                $templateQuestion = $row['template_question'];
                $cells = $row['cells'];
                ?>
                <tr>
                    <td class="col-md-2">
                        <div class="question-wrap">
                            <div class="question-text">
                                    <span class="text-primary" data-tooltip
                                          title="{{$templateQuestion->question}}">
                                        <i class="fa fa-question-circle-o"></i>
                                    </span>

                                {{$templateQuestion->question}}
                            </div>
                        </div>
                    </td>
                    <?php
                    $contributorCellDrawn = false;
                    ?>
                    @foreach($cells as $cell)
                        <?php
                        $user = $cell['user'];
                        $answer = $cell['answer'];
                        $answerStatusCssClass = $cell['answer_status_css_class'];
                        $isContributorTeam = $cell['column_is_contributor_team'];
                        $userIsColumnOwner = $cell['user_is_column_owner'];
                        $userHasAccess = $cell['user_has_access'];

                        // $canView = $cell['can_view'];
                        // $canViewSometimes = $cell['can_view_sometimes'];
                        // $canCreate = $cell['can_create'];
                        // $canCreateSometimes = $cell['can_create_sometimes'];

                        $colSpan = 1;
                        if ($isContributorTeam) {
                            if ($contributorCellDrawn) {
                                continue;
                            }
                            $contributorCellDrawn = true;
                            $colSpan              = $contributorTeamCount;
                        }
                        ?>
                        <td class="{{$answerStatusCssClass}}" colspan="{{$colSpan}}">
                            @if($answer)

                                @if($userHasAccess)
                                    <strong>Score:</strong>
                                    @can('view', [$answer, $item])
                                        {{$answer->score}}/{{$templateQuestion->max_score}}
                                    @else
                                        Not Visible
                                    @endcan
                                    <div class="pull-right">

                                        @include('records.answers.controls.buttons', [
                                        'item' => $answer,
                                        'tile' => $item,
                                        'size' => 'sm',
                                        ])
                                    </div>
                                @endif
                            @else
                                @if($userIsColumnOwner)
                                    @include('records.answers.controls.buttons-create',[
                                    'tile' => $item,
                                    'tileQuestion' => $row['question'],
                                    'size' => 'sm',
                                    ])

                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
</div>
