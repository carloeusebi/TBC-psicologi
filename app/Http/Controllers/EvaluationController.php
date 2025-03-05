<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Evaluations\CreateEvaluationAction;
use App\Actions\Evaluations\DeleteEvaluationAction;
use App\Actions\Evaluations\UpdateEvaluationAction;
use App\Http\Requests\EvaluationRequest;
use App\Http\Resources\EvaluationResource;
use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class EvaluationController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Evaluation::class);

        $evaluation = Evaluation::query()
            ->whereHas('patient', fn (Builder $query) => $query->whereBelongsTo($request->user()))
            ->latest()
            ->get();

        return Inertia::render('evaluations/Index', [
            'evaluations' => EvaluationResource::collection($evaluation),
        ]);
    }

    public function store(EvaluationRequest $request, CreateEvaluationAction $action): RedirectResponse
    {
        $this->authorize('create', Evaluation::class);

        $evaluation = $action->handle($request);

        return to_route('evaluations.show', $evaluation)
            ->with('success', 'Valutazione creata con successo.');
    }

    public function show(Evaluation $evaluation): Response
    {
        $this->authorize('view', $evaluation);

        return Inertia::render('evaluations/Show', [
            'evaluation' => EvaluationResource::make($evaluation),
        ]);
    }

    public function update(EvaluationRequest $request, Evaluation $evaluation, UpdateEvaluationAction $action): RedirectResponse
    {
        $this->authorize('update', $evaluation);

        $action->handle($evaluation, $request);

        return to_route('evaluations.show', $evaluation)
            ->with('success', 'Valutazione modificata con successo.');
    }

    public function destroy(Evaluation $evaluation, DeleteEvaluationAction $action): RedirectResponse
    {
        $this->authorize('delete', $evaluation);

        $action->handle($evaluation);

        return to_route('evaluations.index')
            ->with('success', 'Valutazione eliminata con successo.');
    }
}
