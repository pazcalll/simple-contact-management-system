import { TLeadNote } from "@/types/custom";

export const updateLeadStatus = async (leadId: number, leadStatusId: number): Promise<unknown> => {
    const token = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content;

    const fetcher = await fetch(`/leads/update-lead-status`, {
        method: 'PATCH',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-Request-Format': 'json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify({
            lead_id: leadId,
            lead_status_id: leadStatusId,
        }),
    });

    let data = null;
    if (fetcher.ok) {
        data = await fetcher.json();
        return data as Promise<{message: string}>;
    }

    data = await fetcher.json();
    return data as Promise<{message: string, is_success: true}>
}

export const getLeadNotesJson = async (leadId: number): Promise<unknown> => {
    const fetcher = await fetch(`/leads/${leadId}/notes`, {
        headers: {
            'X-Request-Format': 'json',
            'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content,
        },
    });
    let data = null;
    if (fetcher.ok) {
        data = await fetcher.json();
        return data as Promise<TLeadNote>;
    }
    return (await fetcher.json()) as Promise<{message: string | "failed to get data"}>;
}