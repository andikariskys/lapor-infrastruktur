import numpy as np
from sklearn.cluster import DBSCAN
from sumy.parsers.plaintext import PlaintextParser
from sumy.nlp.tokenizers import Tokenizer
from sumy.summarizers.luhn import LuhnSummarizer
from typing import List
import models

def cluster_and_summarize_reports(reports: List[models.Report]):
    if not reports:
        return []

    # Prepare coordinates for clustering
    # Haversine metric expects radians: [lat, lon]
    coords = np.array([[np.radians(r.latitude), np.radians(r.longitude)] for r in reports])
    
    # epsilon = distance / earth_radius
    # 2.0 km = 2.0 / 6371.0
    kms_per_radian = 6371.0088
    epsilon = 2.0 / kms_per_radian
    
    db = DBSCAN(eps=epsilon, min_samples=2, algorithm='ball_tree', metric='haversine').fit(coords)
    labels = db.labels_

    clusters = {}
    for i, label in enumerate(labels):
        label_val = int(label)
        if label_val not in clusters:
            clusters[label_val] = []
        clusters[label_val].append(reports[i])

    result = []
    
    # Process clusters
    for label, cluster_reports in clusters.items():
        if label == -1:
            # Noise/Individual reports
            for r in cluster_reports:
                result.append({
                    "cluster_id": -1,
                    "summary": r.description,
                    "reports": [r],
                    "latitude": r.latitude,
                    "longitude": r.longitude,
                    "count": 1
                })
            continue

        # Combine descriptions for summarization
        combined_text = " ".join([r.description for r in cluster_reports])
        
        try:
            parser = PlaintextParser.from_string(combined_text, Tokenizer("indonesian"))
            summarizer = LuhnSummarizer()
            # Get 1-2 sentences for summary
            summary_sentences = summarizer(parser.document, 2)
            summary = " ".join([str(s) for s in summary_sentences])
        except Exception:
            # Fallback if summarization fails or language not supported
            summary = combined_text[:200] + "..." if len(combined_text) > 200 else combined_text

        # Average coordinates for the cluster center
        avg_lat = sum(r.latitude for r in cluster_reports) / len(cluster_reports)
        avg_lon = sum(r.longitude for r in cluster_reports) / len(cluster_reports)

        result.append({
            "cluster_id": label,
            "summary": summary if summary else combined_text[:200],
            "reports": cluster_reports,
            "latitude": avg_lat,
            "longitude": avg_lon,
            "count": len(cluster_reports)
        })

    # Sort by count or cluster_id
    result.sort(key=lambda x: x['count'], reverse=True)
    return result
