@props(['blocks' => []])

@if(is_array($blocks) && count($blocks) > 0)
    <div class="space-y-8">
        @foreach($blocks as $block)
            @php
                $type = data_get($block, 'type');
                $data = (array) data_get($block, 'data', []);
            @endphp

            @switch($type)
                @case('hero_terminal')
                    <x-blocks.hero_terminal :data="$data" />
                    @break

                @case('architecture_step_list')
                    <x-blocks.architecture_step_list :data="$data" />
                    @break

                @case('code_artifact_viewer')
                    <x-blocks.code_artifact_viewer :data="$data" />
                    @break

                @case('metric_callout_grid')
                    <x-blocks.metric_callout_grid :data="$data" />
                    @break

                @case('problem_solution_split')
                    <x-blocks.problem_solution_split :data="$data" />
                    @break

                @case('markdown_prose')
                    <x-blocks.markdown_prose :data="$data" />
                    @break

                @case('cta_banner')
                    <x-blocks.cta_banner :data="$data" />
                    @break
            @endswitch
        @endforeach
    </div>
@endif

