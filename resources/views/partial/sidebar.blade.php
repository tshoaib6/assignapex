@php
use Illuminate\Support\Facades\Auth;
use App\Models\CSTRequest;
use App\Models\SelectedChecklist;
use App\Models\TestLogFile;
use App\Models\TesterAssignment;
use App\Models\CstFinalAcceptance;
use App\Models\TeamLeaderEvaluation;
use App\Models\PPdataValidation;
use App\Models\PostProcessorFinalChecklistConfirmation;
use App\Models\PostProcessorReportValidation;

$user = Auth::user();
$todoCount = 0;
$redoCount = 0;

if ($user) {
    $position = optional($user->teamDetail)->position;

    // Redo Task Count
    if ($position === 'Project Manager') {
        $redoCount = CstFinalAcceptance::where('status', 'submitted')
            ->where('decision', '!=', 'accept')
            ->count();
    }

    if ($position === 'Post Processor') {
        $redoCount = TeamLeaderEvaluation::where('status', 'pending')
            ->where('decision', '!=', 'approve')
            ->count();
    }

    if ($position === 'Drive Tester') {
        $redoCount = SelectedChecklist::where('status', 0)
            ->whereHas('cstRequest', function ($query) {
                $query->where('step', 4);
            })->count();
    }

//    if ($position === 'Post Processor') {
//        $task1 = PPdataValidation::where('status', '0')
//            ->where('decision', '!=', 'accept')->count();
//        $task2 = PostProcessorFinalChecklistConfirmation::where('status', '0')
//            ->where('checklist_confirmation', 'not_confirmed')->count();
//        $task3 = PostProcessorReportValidation::where('status', '0')
//            ->where('report_validation_decision', '!=', 'accept')->count();
//
//        $redoCount = $task1 + $task2 + $task3;
//    }

    // Todo Task Count
    if ($user->hasRole('Team')) {
        if ($position === 'Project Manager') {
            $todoCount = CSTRequest::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('assign_to');
            })->where('status', '1')->count(); // Changed status from '0' to '1' to match controller logic
        } elseif ($position === 'Team Lead') {
            $todoCount = SelectedChecklist::where('status', '0')->count();
        } elseif ($position === 'Post Processor') {
            $todoCount = TestLogFile::where('status', '0')->count();
        } elseif ($position === 'Drive Tester') {
            $todoCount = TesterAssignment::where('tester_id', $user->id)
                ->where('status', '0')->count();
        }
    } else {
        $todoCount = CSTRequest::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('assign_to', $user->id);
        })->whereNotNull('assign_to')
          ->whereIn('step', [1, 12])
          ->count();
    }
}

// Menu visibility check
function menuIsVisible($menu) {
    $user = auth()->user();
    if (isset($menu['role'])) {
        $allowedRoles = is_array($menu['role']) ? $menu['role'] : [$menu['role']];
        if (!in_array($user->role, $allowedRoles)) return false;
    }
    if (!empty($menu['permission']) && !$user->can($menu['permission'])) return false;
    if (!empty($menu['children'])) {
        foreach ($menu['children'] as $child) {
            if (menuIsVisible($child)) return true;
        }
        return false;
    }
    return true;
}

// Submenu renderer
function renderSubMenu($menus, $currentUrl, $todoCount = 0, $redoCount = 0) {
    $subMenu = '';
    $GLOBALS['sub_level'] += 1;
    $GLOBALS['active'][$GLOBALS['sub_level']] = '';
    $currentLevel = $GLOBALS['sub_level'];

    foreach ($menus as $menu) {
        if (!menuIsVisible($menu)) continue;

        $text = $menu['text'] ?? '';
        $hasSub = !empty($menu['children']) ? 'has-sub' : '';
        $menuUrl = $menu['url'] ?? '#';
        $menuCaret = $hasSub ? '<span class="menu-caret"><b class="caret"></b></span>' : '';
        $menuText = '<span class="menu-text">' . $text . '</span>';

        // Badge
        $badgeLabel = '';
        if ($text === 'Task Todo') {
            $badgeLabel = '<span class="menu-icon-label badge bg-danger ms-2">' . $todoCount . '</span>';
        } elseif ($text === 'Redo Tasks') {
            $badgeLabel = '<span class="menu-icon-label badge bg-warning ms-2">' . $redoCount . '</span>';
        } elseif (!empty($menu['label'])) {
            $badgeLabel = '<span class="menu-icon-label">' . $menu['label'] . '</span>';
        }

        // Icon
        $menuIcon = !empty($menu['icon']) ? '<span class="menu-icon"><i class="' . $menu['icon'] . '"></i>' . $badgeLabel . '</span>' : '';

        // Submenus
        $subSubMenu = '';
        if (!empty($menu['children'])) {
            $subSubMenu .= '<div class="menu-submenu">';
            $subSubMenu .= renderSubMenu($menu['children'], $currentUrl, $todoCount, $redoCount);
            $subSubMenu .= '</div>';
        }

        $active = ($currentUrl == $menuUrl || !empty($GLOBALS['active'][$currentLevel])) ? 'active' : '';

        $subMenu .= '
        <div class="menu-item ' . $hasSub . ' ' . $active . '">
            <a href="' . $menuUrl . '" class="menu-link">
                ' . $menuIcon . '
                ' . $menuText . '
                ' . $menuCaret . '
            </a>
            ' . $subSubMenu . '
        </div>';
    }

    return $subMenu;
}
@endphp

<!-- Sidebar Start -->
<div id="sidebar" class="app-sidebar">
    <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
        <div class="menu">
            @php
            $currentUrl = '/' . request()->path();
            foreach (config('sidebar.menu') as $menu) {
                if (!menuIsVisible($menu)) continue;

                $GLOBALS['parent_active'] = '';
                $text = $menu['text'] ?? '';
                $hasSub = !empty($menu['children']) ? 'has-sub' : '';
                $menuUrl = $menu['url'] ?? '#';
                $menuCaret = $hasSub ? '<span class="menu-caret"><b class="caret"></b></span>' : '';
                $menuText = '<span class="menu-text">' . $text . '</span>';

                // Badge
                $badgeLabel = '';
                if ($text === 'Task Todo') {
                    $badgeLabel = '<span class="menu-icon-label badge bg-danger ms-2">' . $todoCount . '</span>';
                } elseif ($text === 'Redo Tasks') {
                    $badgeLabel = '<span class="menu-icon-label badge bg-warning ms-2">' . $redoCount . '</span>';
                } elseif (!empty($menu['label'])) {
                    $badgeLabel = '<span class="menu-icon-label">' . $menu['label'] . '</span>';
                }

                $menuIcon = !empty($menu['icon']) ? '<span class="menu-icon"><i class="' . $menu['icon'] . '"></i>' . $badgeLabel . '</span>' : '';
                $menuSubMenu = '';

                if (!empty($menu['children'])) {
                    $GLOBALS['sub_level'] = 0;
                    $menuSubMenu .= '<div class="menu-submenu">';
                    $menuSubMenu .= renderSubMenu($menu['children'], $currentUrl, $todoCount, $redoCount);
                    $menuSubMenu .= '</div>';
                }

                $active = (!empty($menu['url']) && $currentUrl == $menu['url']) ? 'active' : '';
                $active = (empty($active) && !empty($GLOBALS['parent_active'])) ? 'active' : $active;

                if (!empty($menu['is_header'])) {
                    echo '<div class="menu-header">' . $text . '</div>';
                } elseif (!empty($menu['is_divider'])) {
                    echo '<div class="menu-divider"></div>';
                } else {
                    echo '
                    <div class="menu-item ' . $hasSub . ' ' . $active . '">
                        <a href="' . $menuUrl . '" class="menu-link">
                            ' . $menuIcon . '
                            ' . $menuText . '
                            ' . $menuCaret . '
                        </a>
                        ' . $menuSubMenu . '
                    </div>';
                }
            }
            @endphp

            <!-- Optional bottom link -->
            <div class="p-3 px-4 mt-auto hide-on-minified">
                <a href="https://seantheme.com/studio/documentation/index.html"
                    class="btn btn-secondary d-block w-100 fw-600 rounded-pill">
                    <i class="fa fa-code-branch me-1 ms-n1 opacity-5"></i> Documentation
                </a>
            </div>
        </div>
    </div>
    <button class="app-sidebar-mobile-backdrop" data-dismiss="sidebar-mobile"></button>
</div>
