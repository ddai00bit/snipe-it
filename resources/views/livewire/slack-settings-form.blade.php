
    {{-- Page title --}}
    @section('title')
        {{ trans('admin/settings/general.webhook_title') }}
        @parent
    @stop

    @section('header_right')
        <a href="{{ route('settings.index') }}" class="btn btn-primary"> {{ trans('general.back') }}</a>
    @stop


    {{-- Page content --}}
    @section('content')

<div>
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <div class="panel box box-default">
        <div class="box-header with-border">
            <h2 class="box-title">
                <i class="{{$webhook_icon}}"></i> {{ trans('admin/settings/general.webhook', ['app'=>$webhook_name] ) }}
            </h2>
        </div>
        <div class="box-body">
            <div class="col-md-12">
                <p>
                    {!! trans('admin/settings/general.webhook_integration_help',array('webhook_link' => $webhook_link, 'app' => $webhook_name)) !!}
                </p>
                <br>
            </div>

                    <div class="col-md-12" style="border-top: 0px;">
                        @if (session()->has('save'))
                            <div class="alert alert-success fade in">
                                {{session('save')}}
                            </div>
                        @endif

                        @if(session()->has('success'))
                            <div class="alert alert-success fade in">
                                {{session('success')}}
                            </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-danger fade in">
                                {{session('error')}}
                            </div>
                        @endif
                        @if(session()->has('message'))
                            <div class="alert alert-danger fade in">
                                {{session('message')}}
                            </div>
                        @endif

                        <div class="form-group col-md-12">
                            <div class="col-md-3">
                                <label>Integration Option</label>
                            </div>
                            <div class="col-md-6">
                                <select wire:model="webhook_selected"
                                        aria-label="webhook_selected"
                                        class="form-control "
                                >
                                    <option value="Slack">Slack</option>
{{--                                    <option value="Discord">Discord</option>--}}
                                    <option value="General">General Webhook</option>
                                </select>
                            </div>
                            <br><br><br>

                        </div>
                        <form class="form-horizontal" role="form" wire:submit.prevent="submit">
                            {{csrf_field()}}

                            <!--Webhook endpoint-->
                            <div class="form-group{{ $errors->has('webhook_endpoint') ? ' error' : '' }}">
                                <div class="col-md-2">
                                    {{ Form::label('webhook_endpoint', trans('admin/settings/general.webhook_endpoint',['app' => $webhook_selected ])) }}
                                </div>
                                <div class="col-md-8 required">
                                    @if (config('app.lock_passwords')===true)
                                        <p class="text-warning"><i
                                                    class="fas fa-lock"></i> {{ trans('general.feature_disabled') }}</p>
                                        <input type="text" wire:model="webhook_endpoint" class='form-control'
                                               placeholder="{{$webhook_placeholder}}"
                                                value="{{old('webhook_endpoint', $webhook_endpoint)}}">
                                    @else
                                        <input type="text" wire:model="webhook_endpoint" class='form-control'
                                               placeholder="{{$webhook_placeholder}}"
                                                value="{{old('webhook_endpoint', $webhook_endpoint)}}">
                                    @endif
                                    {!! $errors->first('webhook_endpoint', '<span class="alert-msg" aria-hidden="true">:message</span>') !!}
                                </div>
                            </div>

                            <!-- Webhook channel -->
                            <div class="form-group{{ $errors->has('webhook_channel') ? ' error' : '' }}">
                                <div class="col-md-2">
                                    {{ Form::label('webhook_channel', trans('admin/settings/general.webhook_channel',['app' => $webhook_selected ])) }}
                                </div>
                                <div class="col-md-8 required">
                                    @if (config('app.lock_passwords')===true)
                                        <input type="text" wire:model="webhook_channel" class='form-control'
                                               placeholder="#IT-Ops"
                                               value="{{old('webhook_channel', $webhook_channel)}}">
                                        <p class="text-warning"><i
                                                    class="fas fa-lock"></i> {{ trans('general.feature_disabled') }}</p>

                                    @else
                                        <input type="text" wire:model="webhook_channel" class='form-control'
                                               placeholder="#IT-Ops"
                                               value="{{old('webhook_channel', $webhook_channel)}}">
                                    @endif
                                    {!! $errors->first('webhook_channel', '<span class="alert-msg" aria-hidden="true">:message</span>') !!}
                                </div>
                            </div>

                            <!-- Webhook botname -->
                            <div class="form-group{{ $errors->has('webhook_botname') ? ' error' : '' }}">
                                <div class="col-md-2">
                                    {{ Form::label('webhook_botname', trans('admin/settings/general.webhook_botname',['app' => $webhook_selected ])) }}
                                </div>
                                <div class="col-md-8">
                                    @if (config('app.lock_passwords')===true)
                                        <input type="text" wire:model="webhook_botname" class='form-control'
                                               placeholder="Snipe-Bot" {{old('webhook_botname', $webhook_botname)}}>
                                        <p class="text-warning"><i
                                                    class="fas fa-lock"></i> {{ trans('general.feature_disabled') }}</p>

                                    @else
                                        <input type="text" wire:model="webhook_botname" class='form-control'
                                               placeholder="Snipe-Bot" {{old('webhook_botname', $webhook_botname)}}>
                                    @endif
                                    {!! $errors->first('webhook_botname', '<span class="alert-msg" aria-hidden="true">:message</span>') !!}
                                </div><!--col-md-10-->
                            </div>

                            <!--Webhook Integration Test-->
                            @if($webhook_selected == 'Slack' || $webhook_selected == 'Discord')
                                @if($webhook_endpoint != null && $webhook_channel != null)
                                    <div class="form-group">
                                        <div class="col-md-offset-2 col-md-8">
                                            <a href="#" wire:click.prevent="testWebhook"
                                               class="btn btn-default btn-sm pull-left"><span><i class="{{$webhook_icon}}"></i>  {!! trans('admin/settings/general.webhook_test',['app' => $webhook_selected ]) !!}</span></a>
                                            <div wire:loading><span style="padding-left: 5px; font-size: 20px"><i
                                                            class="fas fa-spinner fa-spin"></i></span></div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <div class="box-footer" style="margin-top: 45px;">
                                <div class="text-right col-md-12">
                                    <a class="btn btn-link text-left"
                                       href="{{ route('settings.index') }}">{{ trans('button.cancel') }}</a>
                                    <button type="submit" {{$isDisabled}} class="btn btn-primary"><i
                                                class="fas fa-check icon-white"
                                                aria-hidden="true"></i> {{ trans('general.save') }}</button>
                                </div>
                            </div><!--box-footer-->
                        </form>

                    </div> <!-- /box -->
                </div> <!-- /.col-md-8-->


            @push('scripts')
                <script>
                    // function formatText (icon) {
                    //     return $('<span><i class="fab ' + $(icon.element).data('icon') + '"></i> ' + icon.text + '</span>');
                    // };
                    //
                    // $('.hook_options').select2({
                    //     width: "50%",
                    //     templateSelection: formatText,
                    //     templateResult: formatText
                    // });
                    // $(document).ready(function () {
                    //     $('.webhook_options').select2();
                    //     $('.webhook_options').on('change', function (e) {
                    //         let data = $('#select2').select2("val");
                    //         @this.set('webhook_option', data);
                    //     });
                    // });


                </script>
    @endpush
