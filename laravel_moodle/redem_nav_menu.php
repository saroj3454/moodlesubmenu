 @if(Auth::user()->role_id == 1 || Auth::user()->is_administrator == "yes")
                    <li>
                        <a href="#moodleurl" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <span class="flaticon-settings"></span> Enroll Course
                            
                        </a>
                        <ul class="collapse list-unstyled" id="moodleurl">

 									<li>
                                    <a href="{{url('enroll-course')}}">Enroll Course </a>

                                	</li>
                           
                                <li>
                                    <a href="{{url('moodle-connection')}}">Moodle Connection </a>

                                </li>
                        </ul>
                    </li>
          @endif 