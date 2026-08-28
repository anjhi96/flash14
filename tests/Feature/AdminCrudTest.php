<?php

use App\Models\ContactMessage;
use App\Models\PageSection;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($this->admin);
});

// --- Services ---

test('admin can create, toggle, and delete a service', function () {
    Volt::test('admin.services-manager')
        ->set('serviceTitle', 'Pengembangan Aplikasi Mobile')
        ->set('serviceShortDescription', 'Aplikasi Android & iOS native maupun hybrid.')
        ->set('serviceDescription', 'Layanan pengembangan aplikasi mobile end-to-end.')
        ->set('serviceOrder', 1)
        ->set('serviceIsActive', true)
        ->call('saveService')
        ->assertHasNoErrors();

    $service = Service::firstWhere('title', 'Pengembangan Aplikasi Mobile');
    expect($service)->not->toBeNull();
    expect($service->is_active)->toBeTrue();

    Volt::test('admin.services-manager')->call('toggleServiceActive', $service->id);
    expect($service->fresh()->is_active)->toBeFalse();

    Volt::test('admin.services-manager')->call('deleteService', $service->id);
    expect(Service::find($service->id))->toBeNull();
});

// --- Projects ---

test('admin can create, feature, and delete a project', function () {
    Volt::test('admin.projects-manager')
        ->set('projectTitle', 'Sistem Manajemen Gudang')
        ->set('projectCategory', 'SaaS App')
        ->set('projectDescription', 'Platform manajemen inventaris multi-gudang.')
        ->set('projectOrder', 1)
        ->set('projectIsFeatured', false)
        ->call('saveProject')
        ->assertHasNoErrors();

    $project = Project::firstWhere('title', 'Sistem Manajemen Gudang');
    expect($project)->not->toBeNull();
    expect($project->is_featured)->toBeFalse();

    Volt::test('admin.projects-manager')->call('toggleProjectFeatured', $project->id);
    expect($project->fresh()->is_featured)->toBeTrue();

    Volt::test('admin.projects-manager')->call('deleteProject', $project->id);
    expect(Project::find($project->id))->toBeNull();
});

// --- Team ---

test('admin can create and delete a team member', function () {
    Volt::test('admin.team-manager')
        ->set('teamName', 'Siti Aminah')
        ->set('teamPosition', 'Backend Engineer')
        ->set('teamOrder', 1)
        ->call('saveTeamMember')
        ->assertHasNoErrors();

    $member = TeamMember::firstWhere('name', 'Siti Aminah');
    expect($member)->not->toBeNull();

    Volt::test('admin.team-manager')->call('deleteTeamMember', $member->id);
    expect(TeamMember::find($member->id))->toBeNull();
});

// --- Messages ---

test('admin can view, toggle read state, and delete a contact message', function () {
    $message = ContactMessage::create([
        'name' => 'Pengunjung Situs',
        'email' => 'pengunjung@example.com',
        'message' => 'Saya tertarik dengan layanan Anda.',
        'is_read' => false,
    ]);

    Volt::test('admin.messages-inbox')
        ->call('viewMessage', $message->id)
        ->assertSet('showMessageModal', true);

    expect($message->fresh()->is_read)->toBeTrue();

    Volt::test('admin.messages-inbox')->call('toggleMessageRead', $message->id);
    expect($message->fresh()->is_read)->toBeFalse();

    Volt::test('admin.messages-inbox')->call('deleteMessage', $message->id);
    expect(ContactMessage::find($message->id))->toBeNull();
});

// --- Page Sections ---

test('admin can toggle a page section and update the tech stack list', function () {
    $section = PageSection::create([
        'page' => 'home',
        'section_key' => 'hero',
        'section_name' => 'Hero Banner',
        'is_enabled' => true,
        'order' => 1,
    ]);

    Volt::test('admin.sections-manager')->call('toggleSection', 'hero');
    expect($section->fresh()->is_enabled)->toBeFalse();

    Volt::test('admin.sections-manager')
        ->set('editingTechStack', "Laravel\nLivewire\nTailwind CSS")
        ->call('saveTechStack');

    $techStack = PageSection::where('section_key', 'tech_stack')->first();
    expect($techStack)->not->toBeNull();
    expect($techStack->settings['items'])->toBe(['Laravel', 'Livewire', 'Tailwind CSS']);
});
