Hi
<table width="640" cellpadding="0" cellspacing="0" border="0" class="wrapper" bgcolor="#FFFFFF">
    <h4>{{$mailData['event']->users->full_name}} has sent to Event Invitation</h4>
    <tr>
        <td height="10" style="font-size:10px; line-height:10px;">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="top">

            <table width="900" cellpadding="4" cellspacing="0" border="1" class="container">
                <tr>
                    <th align="center" valign="top" width="20%">
                        Name
                    </th>
                    <td align="center" valign="top">
                        {{$mailData['event']->title}}
                    </td>
                </tr>
                <tr>
                    <th align="center" valign="top">
                        Location
                    </th>
                    <td align="center" valign="top">
                        {{$mailData['event']->event_locations}}
                    </td>
                </tr>
                <tr>
                    <th align="center" valign="top">
                        Event Date
                    </th>
                    <td align="center" valign="top">
                        {{$mailData['event']->event_date}}
                    </td>
                </tr>

            </table>

        </td>
    </tr>
    <tr>
        <td height="10" style="font-size:10px; line-height:10px;">&nbsp;</td>
    </tr>
    {{url('invite?event='.$mailData['event']->_id)}}
</table>

<p>Thanks </p>

<p>Nextneighborhood</p>