import { router } from "@inertiajs/vue3";

export const createAssignedLeads = (leadIds: number[], managerId: number|undefined, supervisorId: number|undefined, teamLeaderId: number|undefined, staffId: number|undefined) => {
    // const fetcher = await fetch('/admins/leads/mass-assignee', {
    //     method: 'POST',
    //     body: JSON.stringify({
    //         lead_ids: leadIds,
    //         manager_id: managerId,
    //         supervisor_id: supervisorId,
    //         team_leader_id: teamLeaderId,
    //         staff_id: staffId,
    //     })
    // })

    router.post('/admins/leads/mass-assignee', {
        lead_ids: leadIds,
        manager_id: managerId,
        supervisor_id: supervisorId,
        team_leader_id: teamLeaderId,
        staff_id: staffId,
    })
}
